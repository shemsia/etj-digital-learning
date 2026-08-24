<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Keep the student foreign key supported
            $table->index('student_id', 'attendances_student_id_index');

            // Remove the old uniqueness rule
            $table->dropUnique('attendances_student_id_date_unique');

            // New uniqueness rule includes the semester
            $table->unique(
                ['student_id', 'semester_id', 'date'],
                'attendances_student_semester_date_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(
                'attendances_student_semester_date_unique'
            );

            $table->unique(
                ['student_id', 'date'],
                'attendances_student_id_date_unique'
            );

            $table->dropIndex(
                'attendances_student_id_index'
            );
        });
    }
};