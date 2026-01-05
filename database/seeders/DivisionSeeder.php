<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Islamic Banking Division',
                'short_name' => 'IBD',
            ],
            [
                'name' => 'Operations Division',
                'short_name' => 'OD',
            ],
            [
                'name' => 'Credit Management Division',
                'short_name' => 'CMD',
            ],
            [
                'name' => 'Human Resource Management Division',
                'short_name' => 'HRMD',
            ],
            [
                'name' => 'Special Asset Management Division',
                'short_name' => 'SAM',
            ],
            [
                'name' => 'Compliance Division',
                'short_name' => 'CD',
            ],
            [
                'name' => 'Treasury Management Division',
                'short_name' => 'TMD',
            ],
            [
                'name' => 'Financial Control Division',
                'short_name' => 'FCD',
            ],
            [
                'name' => 'Information Technology Division',
                'short_name' => 'ITD',
            ],
            [
                'name' => 'Commercial & Retail Banking Division',
                'short_name' => 'CRBD',
            ],
            [
                'name' => 'Credit Administration Division',
                'short_name' => 'CAD',
            ],
            [
                'name' => 'Risk Management Division',
                'short_name' => 'RMD',
            ],
            [
                'name' => 'Audit & Inspection Division',
                'short_name' => 'AID',
            ],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
