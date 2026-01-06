<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Note: Correspondents table removed - using sender_name text field instead

        // 2. Letter Types - Classification of document types
        Schema::create('letter_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 20)->unique();
            $table->boolean('requires_reply')->default(false);
            $table->unsignedSmallInteger('default_days_to_reply')->nullable();
            $table->boolean('is_active')->default(true);
            $table->userTracking();
            $table->softDeletes();
            $table->timestamps();
        });

        // 3. Correspondence Categories - Subject classification
        Schema::create('correspondence_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 20)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('correspondence_categories')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->userTracking();
            $table->softDeletes();
            $table->timestamps();

            $table->index('parent_id');
        });

        // 4. Correspondence Priorities - Urgency levels
        Schema::create('correspondence_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('code', 20)->unique();
            $table->string('color', 20)->default('gray');
            $table->unsignedSmallInteger('sla_hours')->nullable()->comment('Expected response time in hours');
            $table->unsignedTinyInteger('sequence')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Correspondence Statuses - Workflow states
        Schema::create('correspondence_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 30)->unique();
            $table->string('color', 20)->default('gray');
            $table->enum('type', ['Receipt', 'Dispatch', 'Both'])->default('Both');
            $table->boolean('is_initial')->default(false);
            $table->boolean('is_final')->default(false);
            $table->unsignedTinyInteger('sequence')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_active']);
        });

        // 6. Correspondence Number Sequences - Auto-numbering
        Schema::create('correspondence_sequences', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Receipt', 'Dispatch']);
            $table->year('year');
            $table->uuid('division_id')->nullable();
            $table->string('prefix', 20);
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();

            $table->foreign('division_id')->references('id')->on('divisions')->nullOnDelete();
            $table->unique(['type', 'year', 'division_id'], 'correspondence_sequences_unique');
        });

        // 7. Correspondences - Main register (core table)
        Schema::create('correspondences', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Identification
            $table->enum('type', ['Receipt', 'Dispatch']);
            $table->string('register_number', 50)->unique();
            $table->year('year');
            $table->unsignedInteger('serial_number');

            // Letter Details
            $table->foreignId('letter_type_id')->nullable()->constrained('letter_types')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('correspondence_categories')->nullOnDelete();
            $table->string('reference_number', 255)->nullable()->comment('Original letter reference');
            $table->date('letter_date')->nullable()->comment('Date on the letter');
            $table->date('received_date')->nullable()->comment('Date received (for Receipt)');
            $table->date('dispatch_date')->nullable()->comment('Date dispatched (for Dispatch)');
            $table->string('subject', 1000);
            $table->text('description')->nullable();

            // Source/Destination (External) - text field for sender/recipient name
            $table->string('sender_name', 500)->nullable()->comment('External party name');

            // Source/Destination (Internal - for inter-division correspondence)
            $table->uuid('from_division_id')->nullable();
            $table->uuid('to_division_id')->nullable();

            // Regional tracking
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();

            // Assignment and tracking
            $table->foreignId('marked_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('addressed_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('initial_action', 100)->nullable();

            // Status & Priority
            $table->foreignId('status_id')->nullable()->constrained('correspondence_statuses')->nullOnDelete();
            $table->foreignId('priority_id')->nullable()->constrained('correspondence_priorities')->nullOnDelete();
            $table->enum('confidentiality', ['Normal', 'Confidential', 'Secret', 'TopSecret'])->default('Normal');

            // Dates & Deadlines
            $table->date('due_date')->nullable();
            $table->timestamp('closed_at')->nullable();

            // Courier/Delivery
            $table->enum('delivery_mode', ['Hand', 'Courier', 'Post', 'Email', 'Fax', 'Other'])->nullable();
            $table->string('courier_name', 255)->nullable();
            $table->string('courier_tracking', 255)->nullable();

            // Linking
            $table->foreignUuid('parent_id')->nullable()->constrained('correspondences')->nullOnDelete();
            $table->foreignUuid('related_correspondence_id')->nullable()->constrained('correspondences')->nullOnDelete();

            // Current holder tracking
            $table->foreignId('current_holder_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('current_holder_since')->nullable();

            // Reply tracking
            $table->boolean('is_replied')->default(false);
            $table->date('reply_date')->nullable();
            $table->string('reply_reference', 255)->nullable();

            // Meta
            $table->text('remarks')->nullable();
            $table->json('metadata')->nullable();

            $table->userTracking();
            $table->softDeletes();
            $table->timestamps();

            // Additional indexes
            $table->index('region_id');
            $table->index('branch_id');

            // Foreign keys for division UUIDs
            $table->foreign('from_division_id')->references('id')->on('divisions')->nullOnDelete();
            $table->foreign('to_division_id')->references('id')->on('divisions')->nullOnDelete();

            // Indexes
            $table->index(['type', 'year']);
            $table->index('register_number');
            $table->index('reference_number');
            $table->index('letter_date');
            $table->index('received_date');
            $table->index('status_id');
            $table->index('priority_id');
            $table->index('current_holder_id');
            $table->index(['is_replied', 'type']);
            $table->index('due_date');
        });

        // 8. Correspondence Movements - Marking trail (critical for tracking)
        Schema::create('correspondence_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('correspondence_id')->constrained('correspondences')->cascadeOnDelete();
            $table->unsignedSmallInteger('sequence')->default(1);

            // From
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_designation', 255)->nullable();

            // To
            $table->foreignId('to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('to_designation', 255)->nullable();
            $table->uuid('to_division_id')->nullable();

            // Action
            $table->enum('action', [
                'Mark',
                'Forward',
                'Return',
                'ForInfo',
                'ForAction',
                'ForApproval',
                'ForSignature',
                'ForComments',
                'ForReview',
                'ForRecord',
                'ForReply',
                'Escalate',
            ])->default('Mark');
            $table->text('instructions')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->date('expected_response_date')->nullable();

            // Response/Review
            $table->timestamp('received_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('action_taken')->nullable();
            $table->timestamp('action_taken_at')->nullable();
            $table->enum('status', ['Pending', 'Received', 'Reviewed', 'Actioned', 'Returned'])->default('Pending');

            // Meta
            $table->text('remarks')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Foreign key for division UUID
            $table->foreign('to_division_id')->references('id')->on('divisions')->nullOnDelete();

            // Indexes
            $table->index(['correspondence_id', 'sequence']);
            $table->index('to_user_id');
            $table->index('status');
            $table->index('created_at');
        });

        // 9. Movement Comments - Discussion thread on movements
        Schema::create('movement_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movement_id')->constrained('correspondence_movements')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('comment');
            $table->boolean('is_private')->default(false)->comment('Only visible to seniors');
            $table->timestamps();

            $table->index('movement_id');
        });

        // 10. Correspondence Reminders - Deadline alerts
        Schema::create('correspondence_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('correspondence_id')->constrained('correspondences')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('remind_at');
            $table->string('message', 500)->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_sent', 'remind_at']);
            $table->index('correspondence_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correspondence_reminders');
        Schema::dropIfExists('movement_comments');
        Schema::dropIfExists('correspondence_movements');
        Schema::dropIfExists('correspondences');
        Schema::dropIfExists('correspondence_sequences');
        Schema::dropIfExists('correspondence_statuses');
        Schema::dropIfExists('correspondence_priorities');
        Schema::dropIfExists('correspondence_categories');
        Schema::dropIfExists('letter_types');
    }
};
