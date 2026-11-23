<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'student',
                'description' => 'Student trainee',
            ],
            [
                'name' => 'faculty_advisor',
                'description' => 'Faculty internship advisor',
            ],
            [
                'name' => 'industry_mentor',
                'description' => 'Industry internship mentor',
            ],
            [
                'name' => 'administrator',
                'description' => 'System administrator',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}