<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Semester::firstOrCreate(
            ['name' => 'Semester I'],
            ['is_active' => true]
        );

        Semester::firstOrCreate(
            ['name' => 'Semester II'],
            ['is_active' => false]
        );
    }
}