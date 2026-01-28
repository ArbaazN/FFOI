<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name'      => 'Admin',
            'email'     => 'admin@gmail.com',
            'role'      => 'admin',
            'password'  => Hash::make('123456'),
        ]);
        $user->assignRole('admin');

        $subAdmin = User::factory()->create([
            'name'      => 'SubAdmin',
            'email'     => 'subadmin@gmail.com',
            'role'      => 'subadmin',
            'password'  => Hash::make('123456'),
        ]);

        $subAdmin->assignRole('subadmin');
    }
}
