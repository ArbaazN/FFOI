<?php

namespace Database\Seeders;

use App\Models\Admin\BlogCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Academic & Program Insights',
            'Career & Placements',
            'Events',
            'Student Life & Experience',
            'Admission & Application Tips',
            'Industry Trend & Business Insights'
        ];

        foreach ($items as $item) {
            BlogCategory::create([
                'name' => $item,
                'slug' => Str::slug($item),
                'status' => 1
            ]);
        }
    }
}
