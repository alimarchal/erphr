<?php

declare(strict_types=1);

use App\Models\CorrespondenceCategory;
use App\Models\User;
use Spatie\Permission\Models\Permission;

function setupUser()
{
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::firstOrCreate(
        ['name' => 'view settings', 'guard_name' => 'web']
    );
    Permission::firstOrCreate(
        ['name' => 'manage correspondence categories', 'guard_name' => 'web']
    );

    $user = User::factory()->create();
    $user->givePermissionTo(['view settings', 'manage correspondence categories']);

    return $user;
}

test('can list categories', function () {
    $user = setupUser();
    CorrespondenceCategory::factory()->count(3)->create();

    $response = $this->actingAs($user)
        ->get(route('correspondence-categories.index'));

    $response->assertSuccessful();
    $response->assertSee('Categories');
});

test('can show create form', function () {
    $user = setupUser();

    $response = $this->actingAs($user)
        ->get(route('correspondence-categories.create'));

    $response->assertSuccessful();
    $response->assertSee('Create Category');
});

test('can store a new category', function () {
    $user = setupUser();

    $response = $this->actingAs($user)
        ->post(route('correspondence-categories.store'), [
            'name' => 'Test Category',
            'code' => 'TEST',
            'parent_id' => null,
        ]);

    $response->assertRedirectToRoute('correspondence-categories.index');
    expect(CorrespondenceCategory::query()->where('name', 'Test Category')->exists())->toBeTrue();
});

test('can show a category', function () {
    $user = setupUser();
    $category = CorrespondenceCategory::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('correspondence-categories.show', $category));

    $response->assertSuccessful();
    $response->assertSee($category->name);
});

test('can show edit form', function () {
    $user = setupUser();
    $category = CorrespondenceCategory::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('correspondence-categories.edit', $category));

    $response->assertSuccessful();
    $response->assertSee('Edit Category');
});

test('can update a category', function () {
    $user = setupUser();
    $category = CorrespondenceCategory::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($user)
        ->put(route('correspondence-categories.update', $category), [
            'name' => 'Updated Name',
            'code' => $category->code,
            'parent_id' => null,
        ]);

    $response->assertRedirectToRoute('correspondence-categories.index');
    expect($category->fresh()->name)->toBe('Updated Name');
});

test('can toggle category status', function () {
    $user = setupUser();
    $category = CorrespondenceCategory::factory()->create(['is_active' => true]);

    $response = $this->actingAs($user)
        ->patch(route('correspondence-categories.toggle', $category));

    $response->assertRedirectToRoute('correspondence-categories.index');
    expect($category->fresh()->is_active)->toBeFalse();
});

test('denies access to users without permission', function () {
    setupUser();
    $userWithoutPermission = User::factory()->create();

    $response = $this->actingAs($userWithoutPermission)
        ->get(route('correspondence-categories.index'));

    $response->assertForbidden();
});

test('allows super admin access regardless of permission', function () {
    setupUser();
    $superAdmin = User::factory()->create(['is_super_admin' => 'Yes']);

    $response = $this->actingAs($superAdmin)
        ->get(route('correspondence-categories.index'));

    $response->assertSuccessful();
});

test('validates required fields on store', function () {
    $user = setupUser();

    $response = $this->actingAs($user)
        ->post(route('correspondence-categories.store'), [
            'name' => '',
            'code' => '',
            'parent_id' => null,
        ]);

    $response->assertSessionHasErrors('name');
});

test('validates unique name', function () {
    $user = setupUser();
    $existing = CorrespondenceCategory::factory()->create(['name' => 'Unique Name']);

    $response = $this->actingAs($user)
        ->post(route('correspondence-categories.store'), [
            'name' => 'Unique Name',
            'code' => 'NEW',
            'parent_id' => null,
        ]);

    $response->assertSessionHasErrors('name');
});

test('can create hierarchical categories', function () {
    setupUser();
    $parent = CorrespondenceCategory::factory()->create();
    $child = CorrespondenceCategory::factory()->create(['parent_id' => $parent->id]);

    expect($child->parent->id)->toBe($parent->id);
    expect($parent->children->first()->id)->toBe($child->id);
});

test('prevents circular parent references', function () {
    $user = setupUser();
    $category = CorrespondenceCategory::factory()->create();

    $response = $this->actingAs($user)
        ->put(route('correspondence-categories.update', $category), [
            'name' => $category->name,
            'code' => $category->code,
            'parent_id' => $category->id,
        ]);

    $response->assertSessionHasErrors('parent_id');
});
