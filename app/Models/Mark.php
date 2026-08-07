<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Mark extends Model
{
protected $fillable = [
    'student_id',
    'subject_id',
    'teacher_id',
    'score',
    'exam_type',
]; 

public function student(): BelongsTo
{
    return $this->belongsTo(Student::class);
}

public function subject(): BelongsTo
{
    return $this->belongsTo(Subject::class);
}

public function teacher(): BelongsTo
{
    return $this->belongsTo(Teacher::class);
}
}
