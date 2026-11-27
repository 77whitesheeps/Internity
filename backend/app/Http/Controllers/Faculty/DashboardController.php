<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\InternshipPlacement;
use App\Services\OjtProgressService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly OjtProgressService $progressService,
    ) {}

    public function index(Request $request): View
    {
        $supervisorId = $request->user()->id;

        $assignedPlacements = InternshipPlacement::with(['student.user', 'company'])
            ->whereHas('supervisorAssignments', fn (Builder $q) => $q->where('supervisor_id', $supervisorId))
            ->latest('start_date')
            ->get();

        $counts = [
            'assignedStudents' => $assignedPlacements->pluck('student_id')->unique()->count(),
            'activeInterns' => $assignedPlacements->where('status', 'active')->pluck('student_id')->unique()->count(),
        ];

        $recentStudents = $assignedPlacements->take(5)->map(fn (InternshipPlacement $placement) => [
            'placement' => $placement,
            'progress' => $this->progressService->forPlacement($placement),
        ]);

        return view('dashboards.faculty', [
            'counts' => $counts,
            'recentStudents' => $recentStudents,
        ]);
    }
}
