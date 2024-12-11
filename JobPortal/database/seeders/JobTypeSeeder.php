<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobType::create([
            'name' => 'Contract'
        ]);

        JobType::create([
            'name' => 'Full Time'
        ]);

        JobType::create([
            'name' => 'Freelance'
        ]);

        JobType::create([
            'name' => 'Part Time'
        ]);

        JobType::create([
            'name' => 'Remote'
        ]);
    }
}
