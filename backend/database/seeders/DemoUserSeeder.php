<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // Demo accounts should only be created locally.
        if (!app()->environment('local')) {
            return;
        }

        $accounts = [
            [
                'name' => 'Test Student',
                'email' => 'student.test@usm.edu.ph',
                'password' => 'student123',
                'role' => 'student',
            ],
            [
                'name' => 'Test Faculty Advisor',
                'email' => 'faculty.test@usm.edu.ph',
                'password' => 'faculty123',
                'role' => 'faculty_advisor',
            ],
            [
                'name' => 'Test Industry Mentor',
                'email' => 'mentor.test@company.com',
                'password' => 'mentor123',
                'role' => 'industry_mentor',
            ],
        ];

        foreach ($accounts as $account) {
            $role = Role::where('name', $account['role'])
                ->firstOrFail();

            $user = User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $account['password'],
                    'role_id' => $role->id,
                ]
            );

            // Create the required student profile.
            if ($account['role'] === 'student') {
                StudentProfile::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'student_number' => 'TEST-2026-001',
                        'program' => 'BS Computer Science',
                        'year_level' => 4,
                        'contact_number' => '09123456789',
                    ]
                );
            }
        }
    }
}
