<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

public function subjects(): BelongsToMany
{
    return $this->belongsToMany(Subject::class, 'teacher_subject');
}
public function classes(): BelongsToMany
{
    return $this->belongsToMany(
        ClassModel::class,
        'class_teacher',
        'teacher_id',
        'class_id'
    );
}
}