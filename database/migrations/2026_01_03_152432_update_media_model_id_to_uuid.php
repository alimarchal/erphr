<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE media ALTER COLUMN model_id TYPE UUID USING model_id::UUID');
        } else {
            DB::statement('ALTER TABLE `media` MODIFY `model_id` CHAR(36) NOT NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE media ALTER COLUMN model_id TYPE BIGINT USING model_id::BIGINT');
        } else {
            DB::statement('ALTER TABLE `media` MODIFY `model_id` BIGINT UNSIGNED NOT NULL');
        }
    }
};
