<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'employee_number',
    'department',
    'contact_number',
])]
class SupervisorProfile extends Model
{
    /**
     * Get the user account associated with this supervisor profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
