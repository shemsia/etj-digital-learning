<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class ClassModel extends Model
{
protected $table = 'classes';
protected $fillable = [
    'name',
    'grade',
];
public function students(): HasMany
{
    return $this->hasMany(Student::class, 'class_id');
}
public function teachers(): BelongsToMany
{
    return $this->belongsToMany(
        Teacher::class,
        'class_teacher',
        'class_id',
        'teacher_id'
    );
}
}
