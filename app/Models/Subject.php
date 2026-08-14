<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{

protected $fillable = [
'name',
'code',
];
public function marks(): HasMany
{
    return $this->hasMany(Mark::class);
}

public function subjects(): BelongsToMany
{
    return $this->belongsToMany(Subject::class, 'teacher_subject');
}
public function teachers(): BelongsToMany
{
    return $this->belongsToMany(Teacher::class, 'teacher_subject');
}
}
