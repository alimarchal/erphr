<?php

use App\Models\Division;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['is_super_admin' => 'Yes']);
});

test('guests cannot access divisions index', function () {
    $this->get(route('divisions.index'))
        ->assertRedirect(route('login'));
});

test('authenticated users can view divisions index', function () {
    $this->actingAs($this->user);

    Division::factory()->count(3)->create();

    $this->get(route('divisions.index'))
        ->assertOk()
        ->assertViewIs('settings.divisions.index')
        ->assertViewHas('divisions');
});

test('authenticated users can view create division form', function () {
    $this->actingAs($this->user);

    $this->get(route('divisions.create'))
        ->assertOk()
        ->assertViewIs('settings.divisions.create');
});

test('authenticated users can create a division', function () {
    $this->actingAs($this->user);

    $divisionData = [
        'name' => 'Test Division',
        'short_name' => 'TD',
    ];

    $this->post(route('divisions.store'), $divisionData)
        ->assertRedirect(route('divisions.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('divisions', [
        'name' => 'Test Division',
        'short_name' => 'TD',
        'created_by' => $this->user->id,
    ]);
});

test('division creation requires name', function () {
    $this->actingAs($this->user);

    $this->post(route('divisions.store'), [
        'name' => '',
        'short_name' => 'TD',
    ])->assertSessionHasErrors(['name']);
});

test('division creation requires short name', function () {
    $this->actingAs($this->user);

    $this->post(route('divisions.store'), [
        'name' => 'Test Division',
        'short_name' => '',
    ])->assertSessionHasErrors(['short_name']);
});

test('authenticated users can view a division', function () {
    $this->actingAs($this->user);

    $division = Division::factory()->create();

    $this->get(route('divisions.show', $division))
        ->assertOk()
        ->assertViewIs('settings.divisions.show')
        ->assertViewHas('division');
});

test('authenticated users can view edit division form', function () {
    $this->actingAs($this->user);

    $division = Division::factory()->create();

    $this->get(route('divisions.edit', $division))
        ->assertOk()
        ->assertViewIs('settings.divisions.edit')
        ->assertViewHas('division');
});

test('authenticated users can update a division', function () {
    $this->actingAs($this->user);

    $division = Division::factory()->create();

    $updatedData = [
        'name' => 'Updated Division Name',
        'short_name' => 'UDN',
    ];

    $this->put(route('divisions.update', $division), $updatedData)
        ->assertRedirect(route('divisions.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('divisions', [
        'id' => $division->id,
        'name' => 'Updated Division Name',
        'short_name' => 'UDN',
        'updated_by' => $this->user->id,
    ]);
});

test('division update requires name', function () {
    $this->actingAs($this->user);

    $division = Division::factory()->create();

    $this->put(route('divisions.update', $division), [
        'name' => '',
        'short_name' => 'TD',
    ])->assertSessionHasErrors(['name']);
});

test('authenticated users can delete a division', function () {
    $this->actingAs($this->user);

    $division = Division::factory()->create();

    $this->delete(route('divisions.destroy', $division))
        ->assertRedirect(route('divisions.index'))
        ->assertSessionHas('success');

    $this->assertSoftDeleted('divisions', [
        'id' => $division->id,
    ]);
});

test('divisions index can be filtered by name', function () {
    $this->actingAs($this->user);

    Division::factory()->create(['name' => 'Alpha Division']);
    Division::factory()->create(['name' => 'Beta Division']);

    $this->get(route('divisions.index', ['filter' => ['name' => 'Alpha']]))
        ->assertOk()
        ->assertSee('Alpha Division')
        ->assertDontSee('Beta Division');
});

test('divisions index can be filtered by short name', function () {
    $this->actingAs($this->user);

    Division::factory()->create(['name' => 'Alpha Division', 'short_name' => 'AD']);
    Division::factory()->create(['name' => 'Beta Division', 'short_name' => 'BD']);

    $this->get(route('divisions.index', ['filter' => ['short_name' => 'AD']]))
        ->assertOk()
        ->assertSee('Alpha Division')
        ->assertDontSee('Beta Division');
});
