<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\InternshipPlacement;
use App\Models\StudentProfile;
use App\Models\SupervisorAssignment;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisorStudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    private function createStudentWithProfile(string $name, string $studentNumber): StudentProfile
    {
        $user = User::factory()->create(['name' => $name]);

        return $user->studentProfile()->create([
            'student_number' => $studentNumber,
            'program' => 'BS Computer Science',
            'year_level' => 4,
            'contact_number' => '09170000001',
        ]);
    }

    private function createPlacement(
        StudentProfile $student,
        Company $company,
        string $status = 'active',
        ?User $faculty = null,
        ?User $mentor = null
    ): InternshipPlacement {
        $placement = InternshipPlacement::create([
            'student_id' => $student->id,
            'company_id' => $company->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'required_hours' => 486,
            'status' => $status,
        ]);

        if ($faculty) {
            SupervisorAssignment::create([
                'placement_id' => $placement->id,
                'supervisor_id' => $faculty->id,
                'assignment_type' => 'faculty_advisor',
                'assigned_at' => now(),
            ]);
        }

        if ($mentor) {
            SupervisorAssignment::create([
                'placement_id' => $placement->id,
                'supervisor_id' => $mentor->id,
                'assignment_type' => 'industry_mentor',
                'assigned_at' => now(),
            ]);
        }

        return $placement;
    }

    public function test_faculty_and_mentor_dashboards_display_live_assigned_counts(): void
    {
        $faculty = User::factory()->facultyAdvisor()->create();
        $mentor = User::factory()->industryMentor()->create();
        $company = Company::create(['company_name' => 'Acme Labs']);

        $activeStudent = $this->createStudentWithProfile('Maria Santos', '2026-0101');
        $pendingStudent = $this->createStudentWithProfile('Pedro Reyes', '2026-0102');

        $this->createPlacement($activeStudent, $company, 'active', $faculty, $mentor);
        $this->createPlacement($pendingStudent, $company, 'pending', $faculty);

        $this->actingAs($faculty)
            ->get('/faculty/dashboard')
            ->assertOk()
            ->assertViewHas('counts', fn (array $c) => $c['assignedStudents'] === 2 && $c['activeInterns'] === 1)
            ->assertSee('Maria Santos')
            ->assertSee('Pedro Reyes');

        $this->actingAs($mentor)
            ->get('/mentor/dashboard')
            ->assertOk()
            ->assertViewHas('counts', fn (array $c) => $c['assignedInterns'] === 1 && $c['activeInterns'] === 1)
            ->assertSee('Maria Santos')
            ->assertDontSee('Pedro Reyes');
    }

    public function test_supervisors_can_only_view_their_own_assigned_students(): void
    {
        $facultyA = User::factory()->facultyAdvisor()->create();
        $facultyB = User::factory()->facultyAdvisor()->create();
        $mentor = User::factory()->industryMentor()->create();
        $company = Company::create(['company_name' => 'Beta Systems']);

        $studentA = $this->createStudentWithProfile('Ana Cruz', '2026-0201');
        $studentB = $this->createStudentWithProfile('Ben Lopez', '2026-0202');

        $this->createPlacement($studentA, $company, 'active', $facultyA, $mentor);
        $this->createPlacement($studentB, $company, 'active', $facultyB);

        // Faculty A can inspect Student A, forbidden from Student B
        $this->actingAs($facultyA)->get(route('faculty.students.show', $studentA))
            ->assertOk()
            ->assertSee('Ana Cruz')
            ->assertSee('Beta Systems');

        $this->actingAs($facultyA)->get(route('faculty.students.show', $studentB))
            ->assertForbidden();

        // Mentor can inspect Student A, forbidden from Student B
        $this->actingAs($mentor)->get(route('mentor.interns.show', $studentA))
            ->assertOk()
            ->assertSee('Ana Cruz');

        $this->actingAs($mentor)->get(route('mentor.interns.show', $studentB))
            ->assertForbidden();
    }

    public function test_ajax_search_filters_assigned_students_by_name_number_and_status(): void
    {
        $faculty = User::factory()->facultyAdvisor()->create();
        $company = Company::create(['company_name' => 'Gamma Corp']);

        $maria = $this->createStudentWithProfile('Maria Clara', '2026-9001');
        $jose = $this->createStudentWithProfile('Jose Rizal', '2026-9002');

        $this->createPlacement($maria, $company, 'active', $faculty);
        $this->createPlacement($jose, $company, 'completed', $faculty);

        // Filter by name
        $this->actingAs($faculty)
            ->getJson(route('faculty.students.search', ['search' => 'Maria']))
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('data.0.student_number', '2026-9001');

        // Filter by student number
        $this->actingAs($faculty)
            ->getJson(route('faculty.students.search', ['search' => '9002']))
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('data.0.student_name', 'Jose Rizal');

        // Filter by internship status
        $this->actingAs($faculty)
            ->getJson(route('faculty.students.search', ['status' => 'completed']))
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('data.0.status', 'completed');
    }

    public function test_faculty_can_create_placement_with_new_company_and_initial_supervisors(): void
    {
        $faculty = User::factory()->facultyAdvisor()->create();
        $mentor = User::factory()->industryMentor()->create();
        $student = $this->createStudentWithProfile('Clara Mendoza', '2026-0301');

        $this->actingAs($faculty)
            ->post(route('faculty.placements.store'), [
                'student_id' => $student->id,
                'new_company_name' => 'Delta Innovations',
                'new_company_address' => 'Davao City',
                'new_company_email' => 'interns@delta.test',
                'start_date' => '2026-11-01',
                'end_date' => '2027-02-28',
                'required_hours' => 486,
                'status' => 'active',
                'industry_mentor_id' => $mentor->id,
            ])
            ->assertRedirect(route('faculty.placements.index'));

        $this->assertDatabaseHas('companies', ['company_name' => 'Delta Innovations']);
        $placement = InternshipPlacement::where('student_id', $student->id)->firstOrFail();

        // Defaulted faculty advisor + selected industry mentor both assigned
        $this->assertDatabaseHas('supervisor_assignments', [
            'placement_id' => $placement->id,
            'supervisor_id' => $faculty->id,
            'assignment_type' => 'faculty_advisor',
        ]);
        $this->assertDatabaseHas('supervisor_assignments', [
            'placement_id' => $placement->id,
            'supervisor_id' => $mentor->id,
            'assignment_type' => 'industry_mentor',
        ]);
    }

    public function test_faculty_can_update_placement_status_via_ajax(): void
    {
        $faculty = User::factory()->facultyAdvisor()->create();
        $company = Company::create(['company_name' => 'Epsilon Tech']);
        $student = $this->createStudentWithProfile('Diego Silva', '2026-0401');
        $placement = $this->createPlacement($student, $company, 'pending', $faculty);

        $this->actingAs($faculty)
            ->patchJson(route('faculty.placements.status', $placement), [
                'status' => 'active',
            ])
            ->assertOk()
            ->assertJsonPath('status', 'active');

        $this->assertSame('active', $placement->fresh()->status);
    }

    public function test_faculty_can_reassign_supervisors_and_invalid_roles_are_rejected(): void
    {
        $faculty1 = User::factory()->facultyAdvisor()->create();
        $faculty2 = User::factory()->facultyAdvisor()->create();
        $mentor1 = User::factory()->industryMentor()->create();
        $mentor2 = User::factory()->industryMentor()->create();
        $studentUser = User::factory()->create();

        $company = Company::create(['company_name' => 'Zeta Works']);
        $student = $this->createStudentWithProfile('Elena Gomez', '2026-0501');
        $placement = $this->createPlacement($student, $company, 'active', $faculty1, $mentor1);

        // Reject assigning a student user as an industry mentor
        $this->actingAs($faculty1)
            ->putJson(route('faculty.placements.assign', $placement), [
                'faculty_advisor_id' => $faculty2->id,
                'industry_mentor_id' => $studentUser->id,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['industry_mentor_id']);

        // Valid reassignment replaces previous supervisors cleanly
        $this->actingAs($faculty1)
            ->putJson(route('faculty.placements.assign', $placement), [
                'faculty_advisor_id' => $faculty2->id,
                'industry_mentor_id' => $mentor2->id,
            ])
            ->assertOk();

        $this->assertDatabaseMissing('supervisor_assignments', [
            'placement_id' => $placement->id,
            'supervisor_id' => $faculty1->id,
        ]);
        $this->assertDatabaseHas('supervisor_assignments', [
            'placement_id' => $placement->id,
            'supervisor_id' => $faculty2->id,
            'assignment_type' => 'faculty_advisor',
        ]);
        $this->assertDatabaseHas('supervisor_assignments', [
            'placement_id' => $placement->id,
            'supervisor_id' => $mentor2->id,
            'assignment_type' => 'industry_mentor',
        ]);
    }

    public function test_mentors_and_students_cannot_access_faculty_placement_management(): void
    {
        $mentor = User::factory()->industryMentor()->create();
        $student = User::factory()->create();

        $this->actingAs($mentor)->get(route('faculty.placements.index'))->assertForbidden();
        $this->actingAs($student)->get(route('faculty.placements.index'))->assertForbidden();
        $this->actingAs($student)->get(route('faculty.students.index'))->assertForbidden();
        $this->actingAs($student)->get(route('mentor.interns.index'))->assertForbidden();
    }
}
