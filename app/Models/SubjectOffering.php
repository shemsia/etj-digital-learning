<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectOffering extends Model
{
    protected $fillable = [
        'subject_id',
        'semester_id',
        'grade_level',
    ];

    /**
     * A subject offering belongs to a subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * A subject offering belongs to a semester.
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * A subject offering has many modules.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
}