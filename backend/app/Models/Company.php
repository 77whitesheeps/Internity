<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'company_name',
    'address',
    'contact_email',
    'contact_number',
])]
class Company extends Model
{
    /**
     * Get all internship placements associated with this company.
     */
    public function internshipPlacements(): HasMany
    {
        return $this->hasMany(InternshipPlacement::class);
    }
}
