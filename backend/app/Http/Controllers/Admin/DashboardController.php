<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\InternshipPlacement;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $roleCounts = Role::withCount('users')
            ->whereIn('name', ['student', 'faculty_advisor', 'industry_mentor'])
            ->pluck('users_count', 'name');

        $counts = [
            'students' => (int) ($roleCounts['student'] ?? 0),
            'facultyAdvisors' => (int) ($roleCounts['faculty_advisor'] ?? 0),
            'industryMentors' => (int) ($roleCounts['industry_mentor'] ?? 0),
            'companies' => Company::count(),
            'internships' => InternshipPlacement::where('status', 'active')->count(),
        ];

        $recentUsers = User::with('role')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.admin', compact(
            'counts',
            'recentUsers'
        ));
    }
}
