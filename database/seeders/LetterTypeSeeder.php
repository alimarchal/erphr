<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Production-safe seeding: only create if table is empty
        if (LetterType::count() > 0) {
            $this->command->info('Letter types already exist. Skipping seeding.');

            return;
        }

        $letterTypes = [
            [
                'name' => 'Urgent Letter',
                'code' => 'UL',
                'requires_reply' => true,
                'default_days_to_reply' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Routine Letter',
                'code' => 'RL',
                'requires_reply' => false,
                'default_days_to_reply' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Circular',
                'code' => 'CIR',
                'requires_reply' => false,
                'default_days_to_reply' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Memorandum',
                'code' => 'MEM',
                'requires_reply' => true,
                'default_days_to_reply' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Notice',
                'code' => 'NOT',
                'requires_reply' => false,
                'default_days_to_reply' => null,
                'is_active' => true,
            ],
        ];

        foreach ($letterTypes as $letterType) {
            LetterType::create($letterType);
        }

        $this->command->info('Letter types seeded successfully.');
    }
}
