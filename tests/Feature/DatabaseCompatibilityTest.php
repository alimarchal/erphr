<?php

use App\Models\Correspondence;
use App\Models\CorrespondenceCategory;
use App\Models\CorrespondencePriority;
use App\Models\CorrespondenceStatus;
use App\Models\LetterType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    // Create a super admin for testing
    $this->user = User::factory()->create([
        'is_super_admin' => 'Yes',
        'is_active' => 'Yes',
    ]);

    // Ensure basic roles exist
    $this->role = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    $this->user->assignRole($this->role);

    // Create required seed data for correspondence
    $this->status = CorrespondenceStatus::factory()->create(['type' => 'Both', 'is_initial' => true]);
    $this->priority = CorrespondencePriority::factory()->create();
    $this->letterType = LetterType::factory()->create();
    $this->category = CorrespondenceCategory::factory()->create();
});

test('can access main administrative routes on postgres', function () {
    $this->actingAs($this->user);

    $routes = [
        '/correspondence',
        '/settings/users',
        '/settings/roles',
        '/settings/permissions',
    ];

    foreach ($routes as $route) {
        $response = $this->get($route);
        $response->assertStatus(200);
    }
});

test('can create and log correspondence on postgres', function () {
    $this->actingAs($this->user);

    // Verify activity logging works with mixed IDs (BigInt User, UUID Correspondence)
    $response = $this->post('/correspondence', [
        'type' => 'Receipt',
        'receipt_no' => 'REC-COMPAT-001',
        'subject' => 'Postgres Compatibility Test',
        'letter_date' => now()->format('Y-m-d'),
        'received_date' => now()->format('Y-m-d'),
        'sender_name' => 'Postgres Tester',
        'confidentiality' => 'Normal',
        'priority_id' => $this->priority->id,
        'status_id' => $this->status->id,
        'letter_type_id' => $this->letterType->id,
        'category_id' => $this->category->id,
    ]);

    $response->assertRedirect();

    // Check if correspondence was created
    $correspondence = Correspondence::where('subject', 'Postgres Compatibility Test')->first();
    expect($correspondence)->not->toBeNull();
    expect($correspondence->id)->toBeString(); // Should be UUID

    // Check activity log compatibility
    $log = DB::table('activity_log')->where('subject_id', (string) $correspondence->id)->first();
    expect($log)->not->toBeNull();
    expect($log->subject_id)->toBe((string) $correspondence->id);
});

test('can manage users and roles with transactions on postgres', function () {
    $this->actingAs($this->user);

    // Ensure permissions exist
    $p1 = Permission::firstOrCreate(['name' => 'view users', 'guard_name' => 'web']);
    $p2 = Permission::firstOrCreate(['name' => 'edit users', 'guard_name' => 'web']);

    // Create a new role
    $roleResponse = $this->post('/roles', [
        'name' => 'test-postgres-role-'.uniqid(),
        'guard_name' => 'web',
        'permissions' => [$p1->id, $p2->id],
    ]);

    $roleResponse->assertRedirect();
    $role = Role::where('name', 'like', 'test-postgres-role-%')->latest()->first();
    expect($role)->not->toBeNull();

    // Create a new user with that role
    $userResponse = $this->post('/users', [
        'name' => 'Postgres User',
        'email' => 'pguser'.uniqid().'@example.com',
        'password' => 'password',
        'designation' => 'Tester',
        'is_super_admin' => 'No',
        'is_active' => 'Yes',
        'roles' => [$role->id],
    ]);

    $userResponse->assertRedirect();
    $newUser = User::where('name', 'Postgres User')->latest()->first();
    expect($newUser)->not->toBeNull();
    expect($newUser->hasRole($role->name))->toBeTrue();
});
