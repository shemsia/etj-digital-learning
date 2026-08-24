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
    Schema::create('modules', function (Blueprint $table) {
        $table->id();

        $table->foreignId('subject_offering_id')
            ->constrained('subject_offerings')
            ->cascadeOnDelete();

        $table->string('name');

        $table->unsignedTinyInteger('max_mark')->default(100);

        $table->decimal('weight', 5, 2);

        $table->unsignedInteger('order')->default(1);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
