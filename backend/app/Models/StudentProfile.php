<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'student_number',
    'program',
    'year_level',
    'contact_number',
])]
class StudentProfile extends Model
{
    /**
     * Get the user account associated with this student profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the internship placements associated with this student.
     */
    public function internshipPlacements(): HasMany
    {
        return $this->hasMany(InternshipPlacement::class, 'student_id');
    }
}
