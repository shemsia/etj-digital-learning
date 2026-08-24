<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
{
    Schema::create('subject_offerings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('subject_id')
            ->constrained('subjects')
            ->cascadeOnDelete();

        $table->foreignId('semester_id')
            ->constrained('semesters')
            ->cascadeOnDelete();

        $table->unsignedTinyInteger('grade_level');

        $table->timestamps();

        $table->unique([
            'subject_id',
            'semester_id',
            'grade_level'
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_offerings');
    }
};
