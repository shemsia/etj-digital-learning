<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Assessment extends Model
{
    protected $fillable = [
        'module_id',
        'name',
        'max_mark',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
    public function studentMarks(): HasMany
{
    return $this->hasMany(StudentAssessmentMark::class);
}
}