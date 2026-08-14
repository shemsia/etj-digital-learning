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
    Schema::table('marks', function (Blueprint $table) {
        $table->unique(
            ['student_id', 'subject_id', 'exam_type'],
            'marks_student_subject_exam_unique'
        );
    });
}

public function down(): void
{
    Schema::table('marks', function (Blueprint $table) {
        $table->dropUnique('marks_student_subject_exam_unique');
    });
}
};
