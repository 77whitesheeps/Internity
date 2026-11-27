<?php

namespace App\Services;

use App\Models\InternshipPlacement;

/**
 * Computes OJT hour progress for an internship placement.
 *
 * NOTE (Phase 3 pair split):
 * Real hour aggregation depends on the `attendance_sessions` table from the
 * Student Trainee Module 2 (partner's half). Until that migration/model lands,
 * this service returns 0 approved hours so Faculty/Mentor UI and APIs work
 * cleanly and can be wired up in one place later.
 */
class OjtProgressService
{
    /**
     * @return array{
     *     required_hours: float,
     *     approved_hours: float,
     *     remaining_hours: float,
     *     percentage: float,
     *     attendance_module_ready: bool
     * }
     */
    public function forPlacement(?InternshipPlacement $placement): array
    {
        $requiredHours = $placement ? (float) $placement->required_hours : 0.0;

        // TODO(Phase 3 - once partner merges `attendance_sessions`):
        // $approvedMinutes = AttendanceSession::where('student_id', $placement->student_id)
        //     ->where('status', 'approved')
        //     ->sum('rendered_minutes');
        // $approvedHours = round($approvedMinutes / 60, 2);
        $approvedHours = 0.0;

        $remainingHours = max(0.0, round($requiredHours - $approvedHours, 2));
        $percentage = $requiredHours > 0
            ? min(100.0, round(($approvedHours / $requiredHours) * 100, 1))
            : 0.0;

        return [
            'required_hours' => $requiredHours,
            'approved_hours' => $approvedHours,
            'remaining_hours' => $remainingHours,
            'percentage' => $percentage,
            'attendance_module_ready' => false,
        ];
    }
}
