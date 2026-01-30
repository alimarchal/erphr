<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('correspondences', function (Blueprint $table) {
            // Dispatch-specific fields
            $table->string('sending_address', 500)->nullable()->after('sender_name')->comment('Destination address for dispatch');
            $table->string('signed_by', 255)->nullable()->after('sending_address')->comment('Person who signed the dispatch');

            // Receipt-specific fields
            $table->string('sender_designation', 255)->nullable()->comment('Designation of sender for receipt')->after('signed_by');

            $table->string('sender_designation_other', 255)->nullable()->after('sender_designation')->comment('Custom designation if "Another" is selected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correspondences', function (Blueprint $table) {
            $table->dropColumn(['sending_address', 'signed_by', 'sender_designation', 'sender_designation_other']);
        });
    }
};
