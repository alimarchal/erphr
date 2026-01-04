<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Role management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // Permission management
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

            // Assign permissions
            'assign permissions',

            // Correspondence management
            'view correspondence',
            'create correspondence',
            'edit correspondence',
            'delete correspondence',
            'mark correspondence',
            'move correspondence',

            // Settings
            'view settings',
            'manage divisions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions

        // Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        // Super admin gets all permissions via Gate::before in AuthServiceProvider or similar,
        // but we can sync all for clarity if needed.
        $superAdminRole->syncPermissions(Permission::all());

        // Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'view users', 'create users', 'edit users',
            'view roles', 'view permissions',
            'view correspondence', 'create correspondence', 'edit correspondence', 'mark correspondence', 'move correspondence',
            'view settings', 'manage divisions',
        ]);

        // User
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view correspondence', 'create correspondence', 'edit correspondence', 'mark correspondence', 'move correspondence',
        ]);
    }
}
