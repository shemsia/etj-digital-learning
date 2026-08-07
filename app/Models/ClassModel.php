<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ClassModel extends Model
{
protected $table = 'classes';
protected $fillable = [
    'name',
];
public function students(): HasMany
{
    return $this->hasMany(Student::class);
}
}
