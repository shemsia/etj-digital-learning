<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Student extends Model
{
protected $fillable = [
    'user_id',
    'student_id',
    'class_id',
    'gender',
    'photo',
];

public function class(): BelongsTo
{
    return $this->belongsTo(ClassModel::class);
}

public function marks(): HasMany
{
    return $this->hasMany(Mark::class);
}
}
