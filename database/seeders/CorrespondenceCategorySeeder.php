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
        // Target categories to add / normalize (with corrected spellings)
        $targetCategories = [
            'Internship',
            'Training',
            'Recruitment',
            'Transfer and Posting',
            'Staff Advances',
            'Salary',
            'Disciplinary Issue', // corrected from "Diciplinery Issue"
            'Leaves',
            'Staff Circular',
            'Third Party Matters', // normalized capitalization & phrasing
            'Outsource Issue', // corrected from "Out Source Issue"
            'Medical',
            'Legal',
        ];

        // Known misspellings / aliases mapped to the corrected name
        $aliases = [
            'Disciplinary Issue' => ['Diciplinery Issue', 'Disciplinery Issue'],
            'Third Party Matters' => ['Third Party matters', 'Third-Party matters', 'Third Party Matter'],
            'Outsource Issue' => ['Out Source Issue', 'Outsource Issues'],
        ];

        foreach ($targetCategories as $name) {
            $existing = CorrespondenceCategory::query()
                ->whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->first();

            if (! $existing && array_key_exists($name, $aliases)) {
                // Try to find by alias (case-insensitive)
                $existing = CorrespondenceCategory::query()
                    ->where(function ($q) use ($aliases, $name) {
                        foreach ($aliases[$name] as $alias) {
                            $q->orWhereRaw('LOWER(name) = ?', [strtolower($alias)]);
                        }
                    })
                    ->first();
            }

            if ($existing) {
                // Normalize to corrected name and activate
                $existing->name = $name;
                $existing->is_active = true;
                $existing->save();
            } else {
                CorrespondenceCategory::create([
                    'name' => $name,
                    'is_active' => true,
                ]);
            }
        }

        $this->command?->info('Correspondence categories added/normalized successfully.');
    }
}
