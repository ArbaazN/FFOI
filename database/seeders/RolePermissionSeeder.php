<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'web';

        // -----------------------------------
        // Permissions (Clean + Consistent CRUD)
        // -----------------------------------
        $permissions = [

            // User Management
            'user.view', 'user.create', 'user.edit',

            // Campuses (Fixed spelling)
            'campus.view', 'campus.create', 'campus.edit',

            // Programs
            'program.view', 'program.create', 'program.edit',

            // Blog Module
            'blog.view', 'blog.create', 'blog.edit',

            // Pages (consistent CRUD, no page.update)
            'page.view', 'page.edit',

            // Settings
            'settings.view', 'settings.edit',

            'utm.view', 'utm.create', 'utm.edit', 'utm.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => $guard,
            ]);
        }

        // -----------------------------------
        // Roles
        // -----------------------------------
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $subadminRole = Role::firstOrCreate(['name' => 'subadmin', 'guard_name' => $guard]);

        // -----------------------------------
        // Assign ALL permissions to Admin
        // -----------------------------------
        $adminRole->syncPermissions(Permission::all());

        // -----------------------------------
        // Assign default permissions to Subadmin (optional)
        // -----------------------------------
        // $subadminRole->syncPermissions([
        //     'blog.view',
        //     'blog.edit',
        //     'campus.view',
        // ]);

        $viewPermissions = Permission::where('name', 'LIKE', '%.view')->get();
        $subadminRole->syncPermissions($viewPermissions);

        // -----------------------------------
        // Assign roles to default users
        // -----------------------------------
        if ($admin = User::find(1)) {
            if (!$admin->hasRole('admin')) {
                $admin->assignRole('admin');
            }
        }

        if ($subadmin = User::find(2)) {
            if (!$subadmin->hasRole('subadmin')) {
                $subadmin->assignRole('subadmin');
            }
        }

        echo "Roles & Permissions seeded successfully.\n";
    }
}
