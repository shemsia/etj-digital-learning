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
    Schema::create('teacher_subject_offering', function (Blueprint $table) {
        $table->id();

        $table->foreignId('teacher_id')
            ->constrained('teachers')
            ->onDelete('cascade');

        $table->foreignId('subject_offering_id')
            ->constrained('subject_offerings')
            ->onDelete('cascade');

        $table->timestamps();

        $table->unique(['teacher_id', 'subject_offering_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_offering');
    }
};
