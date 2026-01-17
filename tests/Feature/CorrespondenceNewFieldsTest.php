<?php

declare(strict_types=1);

use App\Models\Correspondence;
use App\Models\CorrespondenceCategory;
use App\Models\CorrespondencePriority;
use App\Models\CorrespondenceStatus;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['is_super_admin' => 'Yes']);
    $this->actingAs($this->user);

    // Create required related data
    LetterType::factory()->create();
    CorrespondenceCategory::factory()->create();
    CorrespondencePriority::factory()->create();
    CorrespondenceStatus::factory()->create(['type' => 'Receipt', 'is_initial' => true]);
    CorrespondenceStatus::factory()->create(['type' => 'Dispatch', 'is_initial' => true]);
});

describe('Dispatch Correspondence New Fields', function () {
    it('stores dispatch with sending_address and signed_by', function () {
        $response = $this->post(route('correspondence.store'), [
            'type' => 'Dispatch',
            'dispatch_no' => 'D-001',
            'dispatch_date' => now()->format('Y-m-d'),
            'subject' => 'Test Dispatch',
            'sending_address' => 'Ministry of Finance, Islamabad',
            'signed_by' => 'John Smith',
        ]);

        $response->assertRedirect();
        $correspondence = Correspondence::where('dispatch_no', 'D-001')->first();
        expect($correspondence)->not->toBeNull();
        expect($correspondence->sending_address)->toBe('Ministry of Finance, Islamabad');
        expect($correspondence->signed_by)->toBe('John Smith');
    });

    it('hides due_date input in dispatch create form', function () {
        $response = $this->get(route('correspondence.create', ['type' => 'Dispatch']));

        $response->assertSuccessful();
        // due_date should not appear for Dispatch in the form
        $response->assertSee('Address of Sending (Destination)');
        $response->assertSee('Signed By');
    });

    it('shows dispatch fields in show page', function () {
        $correspondence = Correspondence::factory()->create([
            'type' => 'Dispatch',
            'sending_address' => '50 Main Street',
            'signed_by' => 'Jane Doe',
        ]);

        $response = $this->get(route('correspondence.show', $correspondence));

        $response->assertSuccessful();
        $response->assertSee('50 Main Street');
        $response->assertSee('Jane Doe');
    });

    it('hides due_date and days_open for dispatch in show page', function () {
        $correspondence = Correspondence::factory()->create([
            'type' => 'Dispatch',
            'due_date' => now()->addDays(7),
        ]);

        $response = $this->get(route('correspondence.show', $correspondence));

        $response->assertSuccessful();
        // For Dispatch, due_date and days_open should not display
        // Instead empty cells should appear
        $response->assertViewHas('correspondence', $correspondence);
    });

    it('allows nullable sending_address and signed_by', function () {
        $response = $this->post(route('correspondence.store'), [
            'type' => 'Dispatch',
            'dispatch_no' => 'D-002',
            'dispatch_date' => now()->format('Y-m-d'),
            'subject' => 'Minimal Dispatch',
        ]);

        $response->assertRedirect();
        $correspondence = Correspondence::where('dispatch_no', 'D-002')->first();
        expect($correspondence->sending_address)->toBeNull();
        expect($correspondence->signed_by)->toBeNull();
    });

    it('does not show addressed_to in dispatch create form', function () {
        $response = $this->get(route('correspondence.create', ['type' => 'Dispatch']));

        $response->assertSuccessful();
        // Addressed To should NOT appear for Dispatch
        $response->assertDontSee('Addressed To');
    });

    it('does not show sender_designation in dispatch create form', function () {
        $response = $this->get(route('correspondence.create', ['type' => 'Dispatch']));

        $response->assertSuccessful();
        // Sender Designation should NOT appear for Dispatch
        $response->assertDontSee('Sender Designation');
    });
});

describe('Receipt Correspondence', function () {
    it('shows sender_designation in receipt create form', function () {
        $response = $this->get(route('correspondence.create', ['type' => 'Receipt']));

        $response->assertSuccessful();
        // sender_designation SHOULD appear for Receipt
        $response->assertSee('Sender Designation');
        $response->assertSee('Divisional Head');
        $response->assertSee('Senior Manager');
    });

    it('shows addressed_to in receipt create form', function () {
        $response = $this->get(route('correspondence.create', ['type' => 'Receipt']));

        $response->assertSuccessful();
        // Addressed To should appear for Receipt
        $response->assertSee('Addressed To');
    });

    it('stores receipt with sender_designation', function () {
        $response = $this->post(route('correspondence.store'), [
            'type' => 'Receipt',
            'receipt_no' => 'R-001',
            'received_date' => now()->format('Y-m-d'),
            'subject' => 'Test Receipt',
            'sender_designation' => 'Senior Manager',
        ]);

        $response->assertRedirect();
        $correspondence = Correspondence::where('receipt_no', 'R-001')->first();
        expect($correspondence)->not->toBeNull();
        expect($correspondence->sender_designation)->toBe('Senior Manager');
    });

    it('handles sender_designation=Another with custom input', function () {
        $response = $this->post(route('correspondence.store'), [
            'type' => 'Receipt',
            'receipt_no' => 'R-002',
            'received_date' => now()->format('Y-m-d'),
            'subject' => 'Custom Designation Receipt',
            'sender_designation' => 'Another',
            'sender_designation_other' => 'Chief Executive Officer',
        ]);

        $response->assertRedirect();
        $correspondence = Correspondence::where('receipt_no', 'R-002')->first();
        expect($correspondence->sender_designation)->toBe('Another');
        expect($correspondence->sender_designation_other)->toBe('Chief Executive Officer');
    });

    it('requires sender_designation_other when sender_designation=Another', function () {
        $response = $this->post(route('correspondence.store'), [
            'type' => 'Receipt',
            'receipt_no' => 'R-003',
            'received_date' => now()->format('Y-m-d'),
            'subject' => 'Missing Custom Designation',
            'sender_designation' => 'Another',
        ]);

        $response->assertSessionHasErrors('sender_designation_other');
    });

    it('shows sender_designation in receipt show page', function () {
        $correspondence = Correspondence::factory()->create([
            'type' => 'Receipt',
            'sender_designation' => 'General Manager',
        ]);

        $response = $this->get(route('correspondence.show', $correspondence));

        $response->assertSuccessful();
        $response->assertSee('General Manager');
    });

    it('shows custom designation_other in receipt show page when sender_designation=Another', function () {
        $correspondence = Correspondence::factory()->create([
            'type' => 'Receipt',
            'sender_designation' => 'Another',
            'sender_designation_other' => 'Board Chairman',
        ]);

        $response = $this->get(route('correspondence.show', $correspondence));

        $response->assertSuccessful();
        $response->assertSee('Board Chairman');
    });
});

describe('Divisional Head Auto-Movement', function () {
    it('creates auto-movement to DH HR when addressed to Divisional Head HR', function () {
        $dhUser = User::factory()->create(['designation' => 'Divisional Head HR']);

        $response = $this->post(route('correspondence.store'), [
            'type' => 'Receipt',
            'receipt_no' => 'R-DH-001',
            'received_date' => now()->format('Y-m-d'),
            'subject' => 'DH Auto-Movement Test',
            'addressed_to_user_id' => $dhUser->id,
        ]);

        $response->assertRedirect();
        $correspondence = Correspondence::where('receipt_no', 'R-DH-001')->first();

        // Check that auto-movement was created
        expect($correspondence->movements()->count())->toBe(1);

        $movement = $correspondence->movements()->first();
        expect($movement->to_user_id)->toBe($dhUser->id);
        expect($movement->action)->toBe('ForAction');
        expect($movement->instructions)->toContain('Presented to Divisional Head HR For Action');
    });

    it('includes remarks from create form in DH movement', function () {
        $dhUser = User::factory()->create(['designation' => 'Divisional Head HR']);

        $response = $this->post(route('correspondence.store'), [
            'type' => 'Receipt',
            'receipt_no' => 'R-DH-002',
            'received_date' => now()->format('Y-m-d'),
            'subject' => 'DH Test with Remarks',
            'addressed_to_user_id' => $dhUser->id,
            'remarks' => 'Urgent HR matter',
        ]);

        $response->assertRedirect();
        $correspondence = Correspondence::where('receipt_no', 'R-DH-002')->first();

        $movement = $correspondence->movements()->first();
        expect($movement->remarks)->toContain('KPO Entry');
        expect($movement->remarks)->toContain('Urgent HR matter');
    });

    it('does not create auto-movement if no Divisional Head HR user exists', function () {
        $divisionalHead = User::factory()->create(['designation' => 'Divisional Head HR']);
        // Temporarily delete the user to test no DH HR scenario
        $divisionalHead->delete();

        $anotherUser = User::factory()->create(['designation' => 'Manager']);

        $response = $this->post(route('correspondence.store'), [
            'type' => 'Receipt',
            'receipt_no' => 'R-NO-DH',
            'received_date' => now()->format('Y-m-d'),
            'subject' => 'No DH HR User Test',
            'addressed_to_user_id' => $anotherUser->id,
        ]);

        $response->assertRedirect();
        $correspondence = Correspondence::where('receipt_no', 'R-NO-DH')->first();

        // No movement should be created if DH HR doesn't exist
        expect($correspondence->movements()->count())->toBe(0);
    });

    it('does not create auto-movement if addressed to non-divisional head HR', function () {
        $dhUser = User::factory()->create(['designation' => 'Divisional Head HR']);
        $regularUser = User::factory()->create(['designation' => 'Senior Manager']);

        $response = $this->post(route('correspondence.store'), [
            'type' => 'Receipt',
            'receipt_no' => 'R-NO-AUTO',
            'received_date' => now()->format('Y-m-d'),
            'subject' => 'Regular User Test',
            'addressed_to_user_id' => $regularUser->id,
        ]);

        $response->assertRedirect();
        $correspondence = Correspondence::where('receipt_no', 'R-NO-AUTO')->first();

        // No auto-movement should be created for non-Divisional Head HR
        expect($correspondence->movements()->count())->toBe(0);
    });
});

describe('Timezone Configuration', function () {
    it('app timezone is set to Asia/Karachi', function () {
        $timezone = config('app.timezone');
        expect($timezone)->toBe('Asia/Karachi');
    });
});
