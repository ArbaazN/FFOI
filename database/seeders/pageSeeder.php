<?php

namespace Database\Seeders;

use App\Models\Admin\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class pageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug'  => 'home',
                'file'  => 'home.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'About Us',
                'slug'  => 'about-us',
                'file'  => 'about.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Blogs',
                'slug'  => 'blogs',
                'file'  => 'blog.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Contact Us',
                'slug'  => 'contact-us',
                'file'  => 'contact-us.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Partner With Us',
                'slug'  => 'partner-with-us',
                'file'  => 'partner-with-us.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Centre',
                'slug'  => 'centres',
                'file'  => 'centres.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Faat',
                'slug'  => '/faat/faat-for-b-schools',
                'file'  => 'faat-bschool.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Faat',
                'slug'  => '/faat/faat-for-corporates',
                'file'  => 'faat-corporate.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Faat',
                'slug'  => '/faat/faat-for-students',
                'file'  => 'faat-student.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Corporate Partnerships',
                'slug'  => '/corporate-partnerships',
                'file'  => 'corporate-partnership.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Corporate Engagement at FFOI',
                'slug'  => '/corporate-engagement-at-ffoi',
                'file'  => 'corporate-engagement.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Centre Partnerships',
                'slug'  => '/centre-partnership',
                'file'  => 'centre-partnership.json',
                'show_in_menu' => true
            ]
        ];

        foreach ($pages as $p) {

            // Load JSON from file
            $jsonPath = resource_path("page-templates/{$p['file']}");

            if (!File::exists($jsonPath)) {
                dd("JSON file not found: {$p['file']}");
            }

            $jsonData = File::get($jsonPath);

            // Insert or update page
            Page::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title'   => $p['title'],
                    'show_in_menu' => $p['show_in_menu'],
                    // 'type'    => $p['type'],
                    'content' => $jsonData
                ]
            );
        }

        echo "Pages seeded successfully.\n";
    }
}
