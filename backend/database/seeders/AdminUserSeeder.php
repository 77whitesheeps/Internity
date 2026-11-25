<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('internity.admin.name');
        $email = config('internity.admin.email');
        $password = config('internity.admin.password');

        if (!$name || !$email || !$password) {
            throw new RuntimeException(
                'Administrator credentials must be configured in the environment.'
            );
        }

        $role = Role::where('name', 'administrator')->firstOrFail();

        User::firstOrCreate(
            ['email' => strtolower(trim($email))],
            [
                'name' => $name,
                'password' => $password,
                'role_id' => $role->id,
            ]
        );
    }
}
