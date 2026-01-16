<?php

namespace Database\Seeders;

use App\Models\CorrespondenceCategory;
use Illuminate\Database\Seeder;

class CorrespondenceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Production-safe seeding: only create if table is empty
        if (CorrespondenceCategory::count() > 0) {
            $this->command->info('Correspondence categories already exist. Skipping seeding.');

            return;
        }

        $categories = [
            [
                'name' => 'Internal Correspondence',
                'code' => 'INT',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'External Correspondence',
                'code' => 'EXT',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Official Letters',
                'code' => 'OL',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Circulars',
                'code' => 'CIR',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Notifications',
                'code' => 'NOT',
                'parent_id' => null,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            CorrespondenceCategory::create($category);
        }

        $this->command->info('Correspondence categories seeded successfully.');
    }
}
