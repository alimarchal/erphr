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
        // For PostgreSQL, changing type from bigint to uuid is tricky even when empty.
        // Since tables are empty, we can drop and recreate the columns.
        
        Schema::table('movement_comments', function (Blueprint $table) {
            if (Schema::hasColumn('movement_comments', 'movement_id')) {
                $table->dropForeign(['movement_id']);
                $table->dropColumn('movement_id');
            }
        });

        Schema::table('correspondence_movements', function (Blueprint $table) {
            if (Schema::hasColumn('correspondence_movements', 'id')) {
                // Drop primary key and column
                // In Postgres, simply dropping the column drops the PK too if it was on that column
                $table->dropColumn('id');
            }
        });

        Schema::table('correspondence_movements', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
        });

        Schema::table('movement_comments', function (Blueprint $table) {
            $table->uuid('movement_id')->after('id');
            $table->foreign('movement_id')->references('id')->on('correspondence_movements')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movement_comments', function (Blueprint $table) {
            $table->dropForeign(['movement_id']);
            $table->dropColumn('movement_id');
        });

        Schema::table('correspondence_movements', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('correspondence_movements', function (Blueprint $table) {
            $table->bigIncrements('id')->primary()->first();
        });

        Schema::table('movement_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('movement_id')->after('id');
            $table->foreign('movement_id')->references('id')->on('correspondence_movements')->cascadeOnDelete();
        });
    }
};
