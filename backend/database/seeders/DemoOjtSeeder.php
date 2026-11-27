<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\InternshipPlacement;
use App\Models\StudentProfile;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoOjtSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $studentProfile = StudentProfile::where('student_number', 'TEST-2026-001')->first();
        $faculty = User::where('email', 'faculty.test@usm.edu.ph')->first();
        $mentor = User::where('email', 'mentor.test@company.com')->first();

        if (! $studentProfile) {
            return;
        }

        $company = Company::firstOrCreate(
            ['company_name' => 'Mindanao Tech Solutions Inc.'],
            [
                'address' => 'Kabacan, Cotabato',
                'contact_email' => 'hr@mindanaotech.test',
                'contact_number' => '09171234567',
            ]
        );

        $placement = InternshipPlacement::firstOrCreate(
            [
                'student_id' => $studentProfile->id,
                'company_id' => $company->id,
            ],
            [
                'start_date' => now()->subWeeks(2)->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'required_hours' => 486,
                'status' => 'active',
            ]
        );

        if ($faculty) {
            SupervisorAssignment::firstOrCreate(
                [
                    'placement_id' => $placement->id,
                    'supervisor_id' => $faculty->id,
                    'assignment_type' => 'faculty_advisor',
                ],
                ['assigned_at' => now()]
            );
        }

        if ($mentor) {
            SupervisorAssignment::firstOrCreate(
                [
                    'placement_id' => $placement->id,
                    'supervisor_id' => $mentor->id,
                    'assignment_type' => 'industry_mentor',
                ],
                ['assigned_at' => now()]
            );
        }
    }
}
