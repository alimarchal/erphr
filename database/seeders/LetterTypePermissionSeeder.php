<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LetterTypePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create the manage letter types permission
        Permission::firstOrCreate(
            ['name' => 'manage letter types', 'guard_name' => 'web']
        );

        // Initially sync with admin and super-admin (can be removed later via UI)
        $roles = Role::whereIn('name', ['super-admin', 'admin'])->get();

        foreach ($roles as $role) {
            if (! $role->hasPermissionTo('manage letter types')) {
                $role->givePermissionTo('manage letter types');
            }
        }

        $this->command->info('Letter type permission seeded successfully.');
    }
}
