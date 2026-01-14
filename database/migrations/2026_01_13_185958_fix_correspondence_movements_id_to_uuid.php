<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if id column is already uuid - if so, skip the conversion
        $columns = DB::select("SELECT data_type FROM information_schema.columns WHERE table_name = 'correspondence_movements' AND column_name = 'id'");
        
        if (!empty($columns) && $columns[0]->data_type === 'uuid') {
            // ID is already UUID, just ensure movement_comments.movement_id is also set up correctly
            Schema::table('movement_comments', function (Blueprint $table) {
                if (!Schema::hasColumn('movement_comments', 'movement_id')) {
                    $table->uuid('movement_id')->after('id');
                    $table->foreign('movement_id')->references('id')->on('correspondence_movements')->cascadeOnDelete();
                }
            });
            return;
        }

        // For PostgreSQL, changing type from bigint to uuid is tricky even when empty.
        // First, handle any null values in id column
        DB::statement('DELETE FROM correspondence_movements WHERE id IS NULL');
        
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
