<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CorrespondenceCategoryPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create the manage correspondence categories permission
        Permission::firstOrCreate(
            ['name' => 'manage correspondence categories', 'guard_name' => 'web']
        );

        // Initially sync with admin and super-admin (can be removed later via UI)
        $roles = Role::whereIn('name', ['super-admin', 'admin'])->get();

        foreach ($roles as $role) {
            if (! $role->hasPermissionTo('manage correspondence categories')) {
                $role->givePermissionTo('manage correspondence categories');
            }
        }

        $this->command->info('Correspondence category permission seeded successfully.');
    }
}
