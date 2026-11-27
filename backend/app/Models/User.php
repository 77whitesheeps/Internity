<?php

namespace App\Models;

// Laravel imports
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Relationship imports
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the role assigned to this user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the student's profile, if this user is a student.
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    /**
     * Get the supervisor's profile, if this user is a supervisor.
     */
    public function supervisorProfile(): HasOne
    {
        return $this->hasOne(SupervisorProfile::class);
    }

    /**
     * Get the internship placements supervised by this user.
     */
    public function supervisorAssignments(): HasMany
    {
        return $this->hasMany(SupervisorAssignment::class, 'supervisor_id');
    }

    /**
     * Get the named route for this user's role-specific dashboard.
     *
     * Single source of truth for role -> dashboard routing, shared by login,
     * the /dashboard redirect, and the sidebar navigation shell.
     */
    public function dashboardRoute(): ?string
    {
        return match ($this->role?->name) {
            'administrator' => 'admin.dashboard',
            'faculty_advisor' => 'faculty.dashboard',
            'industry_mentor' => 'mentor.dashboard',
            'student' => 'student.dashboard',
            default => null,
        };
    }

    /**
     * Automatically hash passwords and cast email verification dates.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
