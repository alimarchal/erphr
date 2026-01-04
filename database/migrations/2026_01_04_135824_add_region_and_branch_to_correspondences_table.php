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
        Schema::table('correspondences', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->after('to_division_id')->constrained('regions')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('region_id')->constrained('branches')->nullOnDelete();

            $table->index('region_id');
            $table->index('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correspondences', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['region_id', 'branch_id']);
        });
    }
};
