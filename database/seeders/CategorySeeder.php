<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::query()->firstOrCreate([
            'title' => 'Общие'
        ]);

        Category::query()->firstOrCreate([
            'title' => 'Ковид'
        ]);

        Category::query()->firstOrCreate([
            'title' => 'Шутки'
        ]);
    }
}
