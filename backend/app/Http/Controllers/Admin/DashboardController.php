<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\InternshipPlacement;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'students' => User::whereHas('role', fn ($query) =>
                $query->where('name', 'student')
            )->count(),

            'facultyAdvisors' => User::whereHas('role', fn ($query) =>
                $query->where('name', 'faculty_advisor')
            )->count(),

            'industryMentors' => User::whereHas('role', fn ($query) =>
                $query->where('name', 'industry_mentor')
            )->count(),

            'companies' => Company::count(),

            'internships' => InternshipPlacement::count(),
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
