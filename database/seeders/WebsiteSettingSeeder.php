<?php

namespace Database\Seeders;

use App\Models\Admin\WebsiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        WebsiteSetting::create([
            'site_name' => 'ASBS',
            'site_title' => 'ASBS - Advanced School of Business Studies',
            'email' => 'asbs@google.com',
            'phone' => '9999999999',
            'footer_text' => '© 2025 My Website - All Rights Reserved'
        ]);
    }
}
