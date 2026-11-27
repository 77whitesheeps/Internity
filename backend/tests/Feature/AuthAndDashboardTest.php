<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\InternshipPlacement;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndDashboardTest extends TestCase
{
    use RefreshDatabase;

    private const DASHBOARDS = [
        'administrator' => '/admin/dashboard',
        'faculty_advisor' => '/faculty/dashboard',
        'industry_mentor' => '/mentor/dashboard',
        'student' => '/student/dashboard',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    public function test_user_factory_assigns_valid_role_by_default(): void
    {
        $student = User::factory()->create();
        $admin = User::factory()->administrator()->create();

        $this->assertSame('student', $student->role->name);
        $this->assertSame('administrator', $admin->role->name);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        foreach (['/dashboard', ...array_values(self::DASHBOARDS)] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_frontend_test_route_is_removed(): void
    {
        $this->get('/frontend-test')->assertNotFound();
    }

    public function test_each_role_reaches_only_its_own_dashboard(): void
    {
        foreach (self::DASHBOARDS as $role => $url) {
            $user = User::factory()->withRole($role)->create();

            $this->actingAs($user)->get('/dashboard')->assertRedirect($url);
            $this->actingAs($user)->get($url)
                ->assertOk()
                ->assertSee('name="csrf-token"', false);

            foreach (self::DASHBOARDS as $otherRole => $otherUrl) {
                if ($otherRole !== $role) {
                    $this->actingAs($user)->get($otherUrl)->assertForbidden();
                }
            }
        }
    }

    public function test_login_redirects_by_role_and_normalizes_email(): void
    {
        User::factory()->create([
            'email' => 'student@usm.edu.ph',
            'password' => 'password123',
        ]);

        $this->post('/login', ['email' => ' STUDENT@usm.edu.ph ', 'password' => 'password123'])
            ->assertRedirect('/student/dashboard');

        $this->assertAuthenticated();
    }

    public function test_login_is_rate_limited(): void
    {
        User::factory()->create([
            'email' => 'student@usm.edu.ph',
            'password' => 'password123',
        ]);

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', ['email' => 'student@usm.edu.ph', 'password' => 'wrong'])
                ->assertStatus(302);
        }

        $this->post('/login', ['email' => 'student@usm.edu.ph', 'password' => 'wrong'])
            ->assertStatus(429);
    }

    public function test_registration_creates_student_with_profile_and_hashed_password(): void
    {
        $this->post('/register', [
            'name' => 'Juan Dela Cruz',
            'student_number' => '2026-0001',
            'contact_number' => '09170000000',
            'program' => 'BSCS',
            'year_level' => 4,
            'email' => 'Juan@USM.edu.ph',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'terms' => '1',
        ])->assertRedirect('/login');

        $user = User::where('email', 'juan@usm.edu.ph')->firstOrFail();

        $this->assertSame('student', $user->role->name);
        $this->assertNotSame('secret1234', $user->password);
        $this->assertNotNull($user->studentProfile);
    }

    public function test_registration_enforces_schema_column_length_limits_and_usm_domain(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'student_number' => str_repeat('1', 51),
            'contact_number' => str_repeat('9', 31),
            'program' => str_repeat('A', 101),
            'year_level' => 4,
            'email' => 'outsider@gmail.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'terms' => '1',
        ])->assertSessionHasErrors(['student_number', 'contact_number', 'program', 'email']);
    }

    public function test_admin_dashboard_displays_live_counts_and_active_internships_only(): void
    {
        $admin = User::factory()->administrator()->create();
        $students = User::factory()->count(3)->create();

        $company = Company::create(['company_name' => 'Acme Corp']);
        $profile = $students[0]->studentProfile()->create([
            'student_number' => '2026-1001',
            'program' => 'BSCS',
            'year_level' => 4,
        ]);

        InternshipPlacement::create([
            'student_id' => $profile->id,
            'company_id' => $company->id,
            'start_date' => now()->toDateString(),
            'required_hours' => 486,
            'status' => 'active',
        ]);
        InternshipPlacement::create([
            'student_id' => $profile->id,
            'company_id' => $company->id,
            'start_date' => now()->toDateString(),
            'required_hours' => 486,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertViewHas('counts', fn (array $counts) => $counts['students'] === 3
                && $counts['companies'] === 1
                && $counts['internships'] === 1)
            ->assertSeeInOrder(['Total Students', '3'])
            ->assertSeeInOrder(['Active Internships', '1'])
            ->assertDontSee('Registration records will appear here.');
    }

    public function test_admin_seeder_uses_environment_credentials(): void
    {
        config(['internity.admin' => [
            'name' => 'Root Admin',
            'email' => ' Admin@USM.edu.ph ',
            'password' => 'a-strong-password',
        ]]);

        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', 'admin@usm.edu.ph')->firstOrFail();
        $this->assertSame('administrator', $admin->role->name);
        $this->assertSame('admin.dashboard', $admin->dashboardRoute());
    }
}
