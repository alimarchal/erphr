<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('correspondences')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $update = [];
                // Update receipt_no if it exists and wasn't already prefixed
                if ($row->receipt_no && !str_starts_with($row->receipt_no, 'BAJK/HO/')) {
                    $update['receipt_no'] = 'BAJK/HO/HRMD/2026/' . $row->receipt_no;
                }

                // Update dispatch_no if it exists and wasn't already prefixed
                if ($row->dispatch_no && !str_starts_with($row->dispatch_no, 'BAJK/HO/')) {
                    $update['dispatch_no'] = 'BAJK/HO/HRMD/2026/' . $row->dispatch_no;
                }

                if (!empty($update)) {
                    DB::table('correspondences')->where('id', $row->id)->update($update);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No safe generic rollback as we don't know original formats perfectly
    }
};
