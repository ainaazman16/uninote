<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Data Structures]'],
            ['name' => 'operating Systems'],
            ['name' => 'Database'],
            ['name' => 'Computer Networks'],
            ['name' => 'Software Engineering'],
        ];      

        DB::table('subjects')->insert($subjects);
    }
}
