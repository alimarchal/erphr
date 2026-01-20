<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Permission;

function setupSettingsPermissions(): void
{
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create all permissions that might be checked in settings views
    $permissions = [
        'view settings',
        'manage correspondence categories',
        'manage users',
        'manage roles',
        'manage permissions',
        'manage letter types',
        'manage divisions',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(
            ['name' => $permission, 'guard_name' => 'web']
        );
    }
}

test('guests cannot access settings index', function () {
    $this->get(route('settings.index'))
        ->assertRedirect(route('login'));
});

test('users without view settings permission cannot access settings index', function () {
    setupSettingsPermissions();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.index'))
        ->assertForbidden();
});

test('users with view settings permission can access settings index', function () {
    setupSettingsPermissions();
    $user = User::factory()->create();
    $user->givePermissionTo('view settings');

    $this->actingAs($user)
        ->get(route('settings.index'))
        ->assertSuccessful();
});

test('super admin can access settings index regardless of permission', function () {
    setupSettingsPermissions();
    $superAdmin = User::factory()->create(['is_super_admin' => 'Yes']);

    $this->actingAs($superAdmin)
        ->get(route('settings.index'))
        ->assertSuccessful();
});

test('users without view settings permission cannot access divisions', function () {
    setupSettingsPermissions();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('divisions.index'))
        ->assertForbidden();
});

test('users with only view settings permission cannot access divisions without manage divisions permission', function () {
    setupSettingsPermissions();
    $user = User::factory()->create();
    $user->givePermissionTo('view settings');

    $this->actingAs($user)
        ->get(route('divisions.index'))
        ->assertForbidden();
});

test('users with both view settings and manage divisions permissions can access divisions', function () {
    setupSettingsPermissions();
    $user = User::factory()->create();
    $user->givePermissionTo(['view settings', 'manage divisions']);

    $this->actingAs($user)
        ->get(route('divisions.index'))
        ->assertSuccessful();
});

test('settings navigation link is not visible to users without permission', function () {
    setupSettingsPermissions();
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertDontSee('href="'.route('settings.index').'"', false);
});

test('settings navigation link is visible to users with permission', function () {
    setupSettingsPermissions();
    $user = User::factory()->create();
    $user->givePermissionTo('view settings');

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertSee(route('settings.index'), false);
});
