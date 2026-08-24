<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
  protected $fillable = [
    'subject_offering_id',
    'name',
    'max_mark',
    'weight',
    'order',
];

    /**
     * A module belongs to a subject offering.
     */
    public function subjectOffering(): BelongsTo
    {
        return $this->belongsTo(SubjectOffering::class);
    }

    /**
     * A module can have many assessments.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}