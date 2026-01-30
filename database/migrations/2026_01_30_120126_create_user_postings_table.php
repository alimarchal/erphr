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
        Schema::create('user_postings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->index();
            $table->uuid('division_id')->index();
            $table->string('designation')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false)->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
        });

        // Migration Logic: Assign all existing users to 'HRMD'
        $hrmd = \Illuminate\Support\Facades\DB::table('divisions')
            ->where('short_name', 'HRMD')
            ->first();

        if (!$hrmd) {
            // Try by name or create default
            $hrmd = \Illuminate\Support\Facades\DB::table('divisions')
                ->where('name', 'Human Resource Management Division')
                ->first();

            if (!$hrmd) {
                \Illuminate\Support\Facades\DB::table('divisions')->insert([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'name' => 'Human Resource Management Division',
                    'short_name' => 'HRMD',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => \Illuminate\Support\Facades\DB::table('users')->value('id'), // Nullable if no user exists
                ]);
                $hrmd = \Illuminate\Support\Facades\DB::table('divisions')->where('short_name', 'HRMD')->first();
            } else {
                \Illuminate\Support\Facades\DB::table('divisions')
                    ->where('id', $hrmd->id)
                    ->update(['short_name' => 'HRMD']);
            }
        }

        if ($hrmd) {
            $users = \Illuminate\Support\Facades\DB::table('users')->get();
            foreach ($users as $user) {
                \Illuminate\Support\Facades\DB::table('user_postings')->insert([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'user_id' => $user->id,
                    'division_id' => $hrmd->id,
                    'designation' => $user->designation,
                    'start_date' => now(),
                    'is_current' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_postings');
    }
};
