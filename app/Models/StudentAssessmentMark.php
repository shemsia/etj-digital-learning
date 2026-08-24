<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssessmentMark extends Model
{
    protected $fillable = [
        'student_id',
        'assessment_id',
        'mark',
    ];

    protected $casts = [
        'mark' => 'decimal:2',
    ];

    /**
     * The mark belongs to a student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * The mark belongs to an assessment.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}