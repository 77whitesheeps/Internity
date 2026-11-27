<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\InternshipPlacement;
use App\Models\StudentProfile;
use App\Services\OjtProgressService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class StudentManagementController extends Controller
{
    private const VALID_STATUSES = ['pending', 'active', 'completed', 'cancelled'];

    public function __construct(
        private readonly OjtProgressService $progressService,
    ) {}

    /**
     * Display the logged-in supervisor's assigned students/interns.
     */
    public function index(Request $request): View
    {
        $role = $request->user()->role?->name;
        $isFaculty = $role === 'faculty_advisor';

        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');

        $placements = $this->filteredAssignedPlacements($request->user()->id, $search, $status);
        $records = $this->formatPlacementRecords($placements, $isFaculty);

        return view('supervisor.students.index', [
            'records' => $records,
            'search' => $search,
            'status' => $status,
            'statuses' => self::VALID_STATUSES,
            'isFaculty' => $isFaculty,
            'pageTitle' => $isFaculty ? 'My Students' : 'My Interns',
            'searchUrl' => route($isFaculty ? 'faculty.students.search' : 'mentor.interns.search'),
            'indexUrl' => route($isFaculty ? 'faculty.students.index' : 'mentor.interns.index'),
        ]);
    }

    /**
     * AJAX JSON endpoint for searching and filtering assigned students.
     */
    public function search(Request $request): JsonResponse
    {
        $role = $request->user()->role?->name;
        $isFaculty = $role === 'faculty_advisor';

        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');

        $placements = $this->filteredAssignedPlacements($request->user()->id, $search, $status);

        return response()->json([
            'count' => $placements->count(),
            'data' => $this->formatPlacementRecords($placements, $isFaculty)->values(),
        ]);
    }

    /**
     * Display a specific assigned student's profile, placements, and OJT progress.
     */
    public function show(Request $request, StudentProfile $student): View
    {
        $user = $request->user();
        $isFaculty = $user->role?->name === 'faculty_advisor';

        $assignedPlacements = InternshipPlacement::with([
            'company',
            'supervisorAssignments.supervisor.role',
        ])
            ->where('student_id', $student->id)
            ->whereHas('supervisorAssignments', fn (Builder $q) => $q->where('supervisor_id', $user->id))
            ->latest('start_date')
            ->get();

        // Enforce supervisor-level ownership: only assigned supervisors may inspect this student.
        if ($assignedPlacements->isEmpty()) {
            abort(403, 'You are not assigned to supervise this student.');
        }

        $student->load('user');
        $currentPlacement = $assignedPlacements->firstWhere('status', 'active') ?? $assignedPlacements->first();
        $progress = $this->progressService->forPlacement($currentPlacement);

        return view('supervisor.students.show', [
            'student' => $student,
            'placements' => $assignedPlacements,
            'currentPlacement' => $currentPlacement,
            'progress' => $progress,
            'isFaculty' => $isFaculty,
            'backUrl' => route($isFaculty ? 'faculty.students.index' : 'mentor.interns.index'),
        ]);
    }

    /**
     * Query internship placements assigned to a supervisor with optional search and status filter.
     */
    private function filteredAssignedPlacements(int $supervisorId, string $search, string $status): Collection
    {
        return InternshipPlacement::with([
            'student.user',
            'company',
            'supervisorAssignments.supervisor',
        ])
            ->whereHas('supervisorAssignments', fn (Builder $q) => $q->where('supervisor_id', $supervisorId))
            ->when(in_array($status, self::VALID_STATUSES, true), fn (Builder $q) => $q->where('status', $status))
            ->when($search !== '', function (Builder $q) use ($search) {
                $q->whereHas('student', function (Builder $studentQuery) use ($search) {
                    $studentQuery
                        ->where('student_number', 'like', "%{$search}%")
                        ->orWhereHas('user', fn (Builder $u) => $u->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('start_date')
            ->get();
    }

    /**
     * Transform placements into a uniform structure for Blade and JSON responses.
     */
    private function formatPlacementRecords(Collection $placements, bool $isFaculty): Collection
    {
        $detailRouteName = $isFaculty ? 'faculty.students.show' : 'mentor.interns.show';

        return $placements->map(function (InternshipPlacement $placement) use ($detailRouteName): array {
            $progress = $this->progressService->forPlacement($placement);
            $student = $placement->student;

            return [
                'placement_id' => $placement->id,
                'student_id' => $student->id,
                'student_name' => $student->user?->name ?? 'Unknown Student',
                'student_email' => $student->user?->email ?? '',
                'student_number' => $student->student_number,
                'program' => $student->program,
                'year_level' => $student->year_level,
                'company_name' => $placement->company?->company_name ?? 'Unassigned',
                'status' => $placement->status,
                'start_date' => $placement->start_date?->format('M d, Y') ?? '—',
                'end_date' => $placement->end_date?->format('M d, Y') ?? 'Ongoing',
                'progress' => $progress,
                'detail_url' => route($detailRouteName, $student),
            ];
        });
    }
}
