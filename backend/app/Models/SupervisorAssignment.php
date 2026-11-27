<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'placement_id',
    'supervisor_id',
    'assignment_type',
    'assigned_at',
])]
class SupervisorAssignment extends Model
{
    /**
     * Get the internship placement associated with this assignment.
     */
    public function internshipPlacement(): BelongsTo
    {
        return $this->belongsTo(InternshipPlacement::class, 'placement_id');
    }

    /**
     * Get the supervisor assigned to this placement.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
