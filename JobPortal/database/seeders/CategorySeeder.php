<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Accoutant',
        ]);

        Category::create([
            'name' => 'Construction & Engineering',
        ]);

        Category::create([
            'name' => 'IT/Computers',
        ]);

        Category::create([
            'name' => 'Social Media',
        ]);

        Category::create([
            'name' => 'Telecom',
        ]);
    }
}
