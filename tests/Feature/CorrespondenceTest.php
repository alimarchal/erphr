<?php

use App\Models\Correspondence;
use App\Models\CorrespondenceCategory;
use App\Models\CorrespondencePriority;
use App\Models\CorrespondenceStatus;
use App\Models\Division;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create(['is_super_admin' => 'Yes']);
    $this->letterType = LetterType::factory()->create();
    $this->category = CorrespondenceCategory::factory()->create();
    $this->division = Division::factory()->create();
    $this->status = CorrespondenceStatus::factory()->initial()->create();
    $this->priority = CorrespondencePriority::factory()->create();
});

test('guests cannot access correspondence index', function () {
    $this->get(route('correspondence.index'))
        ->assertRedirect(route('login'));
});

test('authenticated users can view correspondence index', function () {
    $this->actingAs($this->user);

    $this->get(route('correspondence.index'))
        ->assertOk()
        ->assertViewIs('correspondence.index')
        ->assertViewHas('correspondences');
});

test('authenticated users can view create correspondence form', function () {
    $this->actingAs($this->user);

    $this->get(route('correspondence.create'))
        ->assertOk()
        ->assertViewIs('correspondence.create');
});

test('authenticated users can create a receipt correspondence', function () {
    $this->actingAs($this->user);

    $correspondenceData = [
        'type' => 'Receipt',
        'letter_type_id' => $this->letterType->id,
        'category_id' => $this->category->id,
        'sender_name' => 'Test Organization',
        'to_division_id' => $this->division->id,
        'letter_date' => '2025-05-01',
        'received_date' => '2025-05-02',
        'subject' => 'Test Subject for Receipt',
        'status_id' => $this->status->id,
        'priority_id' => $this->priority->id,
    ];

    $this->post(route('correspondence.store'), $correspondenceData)
        ->assertRedirect();

    $this->assertDatabaseHas('correspondences', [
        'type' => 'Receipt',
        'subject' => 'Test Subject for Receipt',
        'letter_type_id' => $this->letterType->id,
        'sender_name' => 'Test Organization',
        'created_by' => $this->user->id,
    ]);
});

test('authenticated users can add attachment to correspondence', function () {
    Storage::fake('public');

    $this->actingAs($this->user);

    $correspondenceData = [
        'type' => 'Receipt',
        'letter_type_id' => $this->letterType->id,
        'category_id' => $this->category->id,
        'sender_name' => 'Test Organization',
        'to_division_id' => $this->division->id,
        'letter_date' => '2025-05-01',
        'received_date' => '2025-05-02',
        'subject' => 'Test Subject for Receipt Attachment',
        'status_id' => $this->status->id,
        'priority_id' => $this->priority->id,
        'attachments' => [
            UploadedFile::fake()->image('attachment.jpg'),
        ],
    ];

    $this->post(route('correspondence.store'), $correspondenceData)
        ->assertRedirect();

    $correspondence = Correspondence::where('subject', 'Test Subject for Receipt Attachment')->first();

    expect($correspondence)->not->toBeNull();
    expect($correspondence->getMedia('attachments'))->toHaveCount(1);

    $this->assertDatabaseHas('media', [
        'collection_name' => 'attachments',
        'model_type' => Correspondence::class,
        'model_id' => $correspondence->id,
    ]);
});

test('authenticated users can create a dispatch correspondence', function () {
    $this->actingAs($this->user);

    $correspondenceData = [
        'type' => 'Dispatch',
        'letter_type_id' => $this->letterType->id,
        'category_id' => $this->category->id,
        'sender_name' => 'Ministry of Finance',
        'to_division_id' => $this->division->id,
        'letter_date' => '2025-05-01',
        'dispatch_date' => '2025-05-02',
        'subject' => 'Test Subject for Dispatch',
        'status_id' => $this->status->id,
        'priority_id' => $this->priority->id,
    ];

    $this->post(route('correspondence.store'), $correspondenceData)
        ->assertRedirect();

    $this->assertDatabaseHas('correspondences', [
        'type' => 'Dispatch',
        'subject' => 'Test Subject for Dispatch',
        'created_by' => $this->user->id,
    ]);
});

test('correspondence creation requires type', function () {
    $this->actingAs($this->user);

    $this->post(route('correspondence.store'), [
        'type' => '',
        'subject' => 'Test Subject',
    ])->assertSessionHasErrors(['type']);
});

test('correspondence creation requires subject', function () {
    $this->actingAs($this->user);

    $this->post(route('correspondence.store'), [
        'type' => 'Receipt',
        'subject' => '',
    ])->assertSessionHasErrors(['subject']);
});

test('correspondence type must be valid', function () {
    $this->actingAs($this->user);

    $this->post(route('correspondence.store'), [
        'type' => 'InvalidType',
        'subject' => 'Test Subject',
    ])->assertSessionHasErrors(['type']);
});

test('authenticated users can view a correspondence', function () {
    $this->actingAs($this->user);

    $correspondence = Correspondence::factory()->create();

    $this->get(route('correspondence.show', $correspondence))
        ->assertOk()
        ->assertViewIs('correspondence.show')
        ->assertViewHas('correspondence');
});

test('authenticated users can view edit correspondence form', function () {
    $this->actingAs($this->user);

    $correspondence = Correspondence::factory()->create();

    $this->get(route('correspondence.edit', $correspondence))
        ->assertOk()
        ->assertViewIs('correspondence.edit')
        ->assertViewHas('correspondence');
});

test('authenticated users can update a correspondence', function () {
    $this->actingAs($this->user);

    $correspondence = Correspondence::factory()->create();

    $updatedData = [
        'type' => $correspondence->type,
        'letter_type_id' => $this->letterType->id,
        'category_id' => $this->category->id,
        'sender_name' => 'Updated Sender Name',
        'to_division_id' => $this->division->id,
        'letter_date' => '2025-05-01',
        'received_date' => '2025-05-02',
        'subject' => 'Updated Subject',
        'status_id' => $this->status->id,
        'priority_id' => $this->priority->id,
    ];

    $this->put(route('correspondence.update', $correspondence), $updatedData)
        ->assertRedirect();

    $this->assertDatabaseHas('correspondences', [
        'id' => $correspondence->id,
        'subject' => 'Updated Subject',
        'sender_name' => 'Updated Sender Name',
        'updated_by' => $this->user->id,
    ]);
});

test('authenticated users can delete a correspondence', function () {
    $this->actingAs($this->user);

    $correspondence = Correspondence::factory()->create();

    $this->delete(route('correspondence.destroy', $correspondence))
        ->assertRedirect(route('correspondence.index', ['type' => $correspondence->type]))
        ->assertSessionHas('success');

    $this->assertSoftDeleted('correspondences', [
        'id' => $correspondence->id,
    ]);
});

test('correspondence index can be filtered by type', function () {
    $this->actingAs($this->user);

    Correspondence::factory()->receipt()->create(['subject' => 'Receipt Letter']);
    Correspondence::factory()->dispatch()->create(['subject' => 'Dispatch Letter']);

    $this->get(route('correspondence.index', ['type' => 'Receipt']))
        ->assertOk()
        ->assertSee('Receipt Letter')
        ->assertDontSee('Dispatch Letter');
});

test('regular users can only see correspondence they are the current holder of', function () {
    $regularUser = User::factory()->create(['is_super_admin' => 'No']);
    $otherUser = User::factory()->create(['is_super_admin' => 'No']);

    // Create the permission if it doesn't exist (for testing)
    \Spatie\Permission\Models\Permission::findOrCreate('view correspondence');
    $regularUser->givePermissionTo('view correspondence');

    Correspondence::factory()->create([
        'subject' => 'My Correspondence',
        'current_holder_id' => $regularUser->id,
    ]);

    Correspondence::factory()->create([
        'subject' => 'Other Correspondence',
        'current_holder_id' => $otherUser->id,
    ]);

    $this->actingAs($regularUser);

    $this->get(route('correspondence.index'))
        ->assertOk()
        ->assertSee('My Correspondence')
        ->assertDontSee('Other Correspondence');
});

test('correspondence generates register number on creation', function () {
    $this->actingAs($this->user);

    $correspondence = Correspondence::factory()->create([
        'type' => 'Receipt',
        'to_division_id' => $this->division->id,
    ]);

    expect($correspondence->register_number)->not->toBeNull();
    expect($correspondence->register_number)->toContain('RR');
});

test('dispatch correspondence generates register number with DR prefix', function () {
    $this->actingAs($this->user);

    $correspondence = Correspondence::factory()->create([
        'type' => 'Dispatch',
        'to_division_id' => $this->division->id,
    ]);

    expect($correspondence->register_number)->not->toBeNull();
    expect($correspondence->register_number)->toContain('DR');
});

test('users who receive correspondence can mark it', function () {
    // Create users
    $creator = User::factory()->create();
    $rajaSaddam = User::factory()->create(['name' => 'Raja Saddam']);

    // Give permissions
    \Spatie\Permission\Models\Permission::findOrCreate('view correspondence');
    \Spatie\Permission\Models\Permission::findOrCreate('mark correspondence');
    $creator->givePermissionTo(['view correspondence', 'mark correspondence']);
    $rajaSaddam->givePermissionTo(['view correspondence', 'mark correspondence']);

    // Create correspondence and forward to Raja Saddam
    $correspondence = Correspondence::factory()->create(['created_by' => $creator->id]);
    $correspondence->markTo($rajaSaddam, 'ForAction', 'Please review this');

    // Raja Saddam should be able to mark it to someone else
    $this->actingAs($rajaSaddam);
    $anotherUser = User::factory()->create();

    $this->post(route('correspondence.mark', $correspondence), [
        'to_user_id' => $anotherUser->id,
        'action' => 'Forward',
        'instructions' => 'For your action',
    ])->assertRedirect()
        ->assertSessionHas('success');
});

test('status update creates movement with valid action', function () {
    $this->actingAs($this->user);

    $correspondence = Correspondence::factory()->create(['created_by' => $this->user->id]);
    $newStatus = CorrespondenceStatus::factory()->create();

    $this->put(route('correspondence.updateStatus', $correspondence), [
        'status_id' => $newStatus->id,
        'remarks' => 'Test status change',
    ])->assertRedirect()
        ->assertSessionHas('success', 'Correspondence status updated successfully.');

    // Verify movement was created with valid action
    $this->assertDatabaseHas('correspondence_movements', [
        'correspondence_id' => $correspondence->id,
        'action' => 'ForRecord', // Should be valid enum value, not 'Status Update'
        'status' => 'Actioned',
    ]);
});

test('status update shows detailed error in debug mode', function () {
    // Temporarily enable debug mode
    config(['app.debug' => true]);

    $this->actingAs($this->user);

    // Create a correspondence with invalid setup to trigger error
    $correspondence = Correspondence::factory()->create(['created_by' => $this->user->id]);

    // Mock to force an error
    \DB::shouldReceive('beginTransaction')->andThrow(new \Exception('Test error message'));

    $newStatus = CorrespondenceStatus::factory()->create();

    $response = $this->put(route('correspondence.updateStatus', $correspondence), [
        'status_id' => $newStatus->id,
    ]);

    // Should show detailed error when debug is enabled
    $response->assertSessionHas('error', function ($message) {
        return str_contains($message, 'Test error message');
    });
});
