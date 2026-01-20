<?php

declare(strict_types=1);

use App\Models\LetterType;
use App\Models\User;
use Spatie\Permission\Models\Permission;

function setupUserForLetterTypes()
{
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::firstOrCreate(
        ['name' => 'view settings', 'guard_name' => 'web']
    );
    Permission::firstOrCreate(
        ['name' => 'manage letter types', 'guard_name' => 'web']
    );

    $user = User::factory()->create(['is_active' => 'Yes']);
    $user->givePermissionTo(['view settings', 'manage letter types']);

    return $user;
}

test('can list letter types', function () {
    $user = setupUserForLetterTypes();
    LetterType::factory()->count(3)->create();

    $response = $this->actingAs($user)
        ->get(route('letter-types.index'));

    $response->assertSuccessful();
    $response->assertSee('Letter Types');
});

test('can show create form', function () {
    $user = setupUserForLetterTypes();

    $response = $this->actingAs($user)
        ->get(route('letter-types.create'));

    $response->assertSuccessful();
    $response->assertSee('Create Letter Type');
});

test('can store a new letter type', function () {
    $user = setupUserForLetterTypes();

    $response = $this->actingAs($user)
        ->post(route('letter-types.store'), [
            'name' => 'Test Letter Type',
            'code' => 'TEST',
            'requires_reply' => true,
            'default_days_to_reply' => 5,
            'is_active' => true,
        ]);

    $response->assertRedirectToRoute('letter-types.index');
    expect(LetterType::query()->where('name', 'Test Letter Type')->exists())->toBeTrue();
});

test('can show a letter type', function () {
    $user = setupUserForLetterTypes();
    $letterType = LetterType::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('letter-types.show', $letterType));

    $response->assertSuccessful();
    $response->assertSee($letterType->name);
});

test('can show edit form', function () {
    $user = setupUserForLetterTypes();
    $letterType = LetterType::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('letter-types.edit', $letterType));

    $response->assertSuccessful();
    $response->assertSee('Edit Letter Type');
});

test('can update a letter type', function () {
    $user = setupUserForLetterTypes();
    $letterType = LetterType::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($user)
        ->put(route('letter-types.update', $letterType), [
            'name' => 'Updated Name',
            'code' => $letterType->code,
            'requires_reply' => true,
            'default_days_to_reply' => 7,
            'is_active' => true,
        ]);

    $response->assertRedirectToRoute('letter-types.index');
    expect($letterType->fresh()->name)->toBe('Updated Name');
});

test('can toggle letter type status', function () {
    $user = setupUserForLetterTypes();
    $letterType = LetterType::factory()->create(['is_active' => true]);

    $response = $this->actingAs($user)
        ->patch(route('letter-types.toggle', $letterType));

    $response->assertRedirectToRoute('letter-types.index');
    expect($letterType->fresh()->is_active)->toBeFalse();
});

test('denies access to users without permission', function () {
    setupUserForLetterTypes();
    $userWithoutPermission = User::factory()->create();

    $response = $this->actingAs($userWithoutPermission)
        ->get(route('letter-types.index'));

    $response->assertForbidden();
});

test('allows super admin access regardless of permission', function () {
    setupUserForLetterTypes();
    $superAdmin = User::factory()->create(['is_super_admin' => 'Yes']);

    $response = $this->actingAs($superAdmin)
        ->get(route('letter-types.index'));

    $response->assertSuccessful();
});

test('validates required fields on store', function () {
    $user = setupUserForLetterTypes();

    $response = $this->actingAs($user)
        ->post(route('letter-types.store'), [
            'name' => '',
            'code' => '',
        ]);

    $response->assertSessionHasErrors('name');
});

test('validates unique name', function () {
    $user = setupUserForLetterTypes();
    $existing = LetterType::factory()->create(['name' => 'Unique Name']);

    $response = $this->actingAs($user)
        ->post(route('letter-types.store'), [
            'name' => 'Unique Name',
            'code' => 'NEW',
        ]);

    $response->assertSessionHasErrors('name');
});

test('validates unique code', function () {
    $user = setupUserForLetterTypes();
    $existing = LetterType::factory()->create(['code' => 'UNIQUE']);

    $response = $this->actingAs($user)
        ->post(route('letter-types.store'), [
            'name' => 'New Type',
            'code' => 'UNIQUE',
        ]);

    $response->assertSessionHasErrors('code');
});

test('validates default days to reply is numeric', function () {
    $user = setupUserForLetterTypes();

    $response = $this->actingAs($user)
        ->post(route('letter-types.store'), [
            'name' => 'Test Type',
            'code' => 'TEST',
            'default_days_to_reply' => 'invalid',
        ]);

    $response->assertSessionHasErrors('default_days_to_reply');
});
