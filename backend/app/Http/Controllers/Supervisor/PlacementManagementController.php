<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\StoreInternshipPlacementRequest;
use App\Http\Requests\Supervisor\UpdatePlacementStatusRequest;
use App\Http\Requests\Supervisor\UpdateSupervisorAssignmentRequest;
use App\Models\Company;
use App\Models\InternshipPlacement;
use App\Models\StudentProfile;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PlacementManagementController extends Controller
{
    private const VALID_STATUSES = ['pending', 'active', 'completed', 'cancelled'];

    /**
     * Display internship placements and student-supervisor assignments.
     */
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', '');
        $search = trim((string) $request->query('search', ''));

        $placements = InternshipPlacement::with([
            'student.user',
            'company',
            'supervisorAssignments.supervisor',
        ])
            ->when(in_array($status, self::VALID_STATUSES, true), fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested
                        ->whereHas('student', function ($s) use ($search) {
                            $s->where('student_number', 'like', "%{$search}%")
                                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                        })
                        ->orWhereHas('company', fn ($c) => $c->where('company_name', 'like', "%{$search}%"));
                });
            })
            ->latest('created_at')
            ->get();

        $students = StudentProfile::with('user')->orderBy('student_number')->get();
        $companies = Company::orderBy('company_name')->get();

        $facultyAdvisors = User::whereHas('role', fn ($q) => $q->where('name', 'faculty_advisor'))
            ->orderBy('name')
            ->get();

        $industryMentors = User::whereHas('role', fn ($q) => $q->where('name', 'industry_mentor'))
            ->orderBy('name')
            ->get();

        return view('supervisor.placements.index', [
            'placements' => $placements,
            'students' => $students,
            'companies' => $companies,
            'facultyAdvisors' => $facultyAdvisors,
            'industryMentors' => $industryMentors,
            'statuses' => self::VALID_STATUSES,
            'status' => $status,
            'search' => $search,
        ]);
    }

    /**
     * Create a new internship placement and optionally assign supervisors transactionally.
     */
    public function store(StoreInternshipPlacementRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request): void {
            $companyId = $validated['company_id'] ?? null;

            if (! $companyId && ! empty($validated['new_company_name'])) {
                $company = Company::create([
                    'company_name' => trim($validated['new_company_name']),
                    'address' => $validated['new_company_address'] ?? null,
                    'contact_email' => $validated['new_company_email'] ?? null,
                    'contact_number' => $validated['new_company_contact'] ?? null,
                ]);
                $companyId = $company->id;
            }

            $placement = InternshipPlacement::create([
                'student_id' => $validated['student_id'],
                'company_id' => $companyId,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'required_hours' => $validated['required_hours'],
                'status' => $validated['status'],
            ]);

            $facultyAdvisorId = $validated['faculty_advisor_id']
                ?? ($request->user()->role?->name === 'faculty_advisor' ? $request->user()->id : null);

            $this->syncAssignment($placement, 'faculty_advisor', $facultyAdvisorId);
            $this->syncAssignment($placement, 'industry_mentor', $validated['industry_mentor_id'] ?? null);
        });

        return redirect()
            ->route('faculty.placements.index')
            ->with('status', 'Internship placement created successfully.');
    }

    /**
     * Update a placement's status asynchronously via AJAX.
     */
    public function updateStatus(
        UpdatePlacementStatusRequest $request,
        InternshipPlacement $placement
    ): JsonResponse {
        $placement->update([
            'status' => $request->validated('status'),
        ]);

        return response()->json([
            'message' => 'Internship status updated.',
            'placement_id' => $placement->id,
            'status' => $placement->status,
        ]);
    }

    /**
     * Assign or reassign a placement's Faculty Advisor and Industry Mentor transactionally.
     */
    public function assignSupervisors(
        UpdateSupervisorAssignmentRequest $request,
        InternshipPlacement $placement
    ): RedirectResponse|JsonResponse {
        $validated = $request->validated();

        DB::transaction(function () use ($placement, $validated): void {
            $this->syncAssignment($placement, 'faculty_advisor', $validated['faculty_advisor_id'] ?? null);
            $this->syncAssignment($placement, 'industry_mentor', $validated['industry_mentor_id'] ?? null);
        });

        if ($request->expectsJson()) {
            $placement->load('supervisorAssignments.supervisor');

            return response()->json([
                'message' => 'Supervisor assignments updated successfully.',
                'placement_id' => $placement->id,
                'faculty_advisor' => $placement->supervisorAssignments
                    ->firstWhere('assignment_type', 'faculty_advisor')?->supervisor?->name,
                'industry_mentor' => $placement->supervisorAssignments
                    ->firstWhere('assignment_type', 'industry_mentor')?->supervisor?->name,
            ]);
        }

        return redirect()
            ->route('faculty.placements.index')
            ->with('status', 'Supervisor assignments updated successfully.');
    }

    /**
     * Replace a placement's assignment for a given supervisor type cleanly inside a transaction.
     */
    private function syncAssignment(InternshipPlacement $placement, string $type, ?int $supervisorId): void
    {
        SupervisorAssignment::where('placement_id', $placement->id)
            ->where('assignment_type', $type)
            ->delete();

        if ($supervisorId !== null) {
            SupervisorAssignment::create([
                'placement_id' => $placement->id,
                'supervisor_id' => $supervisorId,
                'assignment_type' => $type,
                'assigned_at' => now(),
            ]);
        }
    }
}
