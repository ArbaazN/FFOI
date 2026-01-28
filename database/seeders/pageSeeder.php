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
                'title' => 'Placements',
                'slug'  => 'placements',
                'file'  => 'placements.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Events & Engagement',
                'slug'  => 'events-and-engagement',
                'file'  => 'events.json',
                'show_in_menu' => true
            ],
            // Learning Experience Pages
            [
                'title' => 'IEDC - Learning Experience',
                'slug'  => 'learning-experience/iedc',
                'file'  => 'iedc.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'CEEMAN Membership - Learning Experience',
                'slug'  => 'learning-experience/ceeman-membership',
                'file'  => 'ceeman-membership.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Pedagogy - Learning Experience',
                'slug'  => 'learning-experience/pedagogy',
                'file'  => 'pedagogy.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Internship and Projects - Learning Experience',
                'slug'  => 'learning-experience/internship-and-projects',
                'file'  => 'internship-and-projects.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'ASBS Mentoria - Faculty',
                'slug'  => 'faculty/asbs-mentoria',
                'file'  => 'asbs-mentoria.json',
                'show_in_menu' => true
            ],

            // Who Should Apply Pages
            [
                'title' => 'Who Should Apply - Final Year Students',
                'slug'  => 'who-should-apply/final-year',
                'file'  => 'final-year.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Who Should Apply - Recent Graduates',
                'slug'  => 'who-should-apply/recent-graduates',
                'file'  => 'recent-graduates.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Who Should Apply - Working Professionals',
                'slug'  => 'who-should-apply/working-professionals',
                'file'  => 'working-professionals.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Who Should Apply - Entrepreneurs',
                'slug'  => 'who-should-apply/entrepreneurs',
                'file'  => 'entrepreneurs.json',
                'show_in_menu' => true
            ],
            [
                'title' => 'Who Should Apply - Career Changers',
                'slug'  => 'who-should-apply/career-changers',
                'file'  => 'career-changers.json',
                'show_in_menu' => true
            ],

            // Partner With Us Page
            [
                'title' => 'Partner With Us',
                'slug'  => 'partner-with-us',
                'file'  => 'partner-with-us.json',
                'show_in_menu' => true
            ],

            [
                'title' => 'Settings',
                'slug'  => 'setting',
                'file'  => 'settings.json',
                'show_in_menu' => false
            ],

            [
                'title' => 'common',
                'slug'  => 'common',
                'file'  => 'common.json',
                'show_in_menu' => false
            ],
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
