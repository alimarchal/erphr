<?php

declare(strict_types=1);

use App\Models\CorrespondenceCategory;
use Database\Seeders\CorrespondenceCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

it('creates missing correspondence categories with corrected names', function () {
    // Ensure DB starts empty
    expect(CorrespondenceCategory::count())->toBe(0);

    // Run specific seeder
    $this->seed(CorrespondenceCategorySeeder::class);

    $expected = [
        'Internship',
        'Training',
        'Recruitment',
        'Transfer and Posting',
        'Staff Advances',
        'Salary',
        'Disciplinary Issue',
        'Leaves',
        'Staff Circular',
        'Third Party Matters',
        'Outsource Issue',
        'Medical',
        'Legal',
    ];

    foreach ($expected as $name) {
        expect(CorrespondenceCategory::where('name', $name)->exists())->toBeTrue();
    }
})->group('seeders', 'correspondence');

it('normalizes misspelled category names', function () {
    // Pre-seed misspelled variants
    CorrespondenceCategory::create(['name' => 'Diciplinery Issue', 'is_active' => false]);
    CorrespondenceCategory::create(['name' => 'Third Party matters', 'is_active' => false]);
    CorrespondenceCategory::create(['name' => 'Out Source Issue', 'is_active' => false]);

    // Run specific seeder
    $this->seed(CorrespondenceCategorySeeder::class);

    expect(CorrespondenceCategory::where('name', 'Disciplinary Issue')->exists())->toBeTrue();
    expect(CorrespondenceCategory::where('name', 'Third Party Matters')->exists())->toBeTrue();
    expect(CorrespondenceCategory::where('name', 'Outsource Issue')->exists())->toBeTrue();

    // Check they are active after normalization
    expect(CorrespondenceCategory::where('name', 'Disciplinary Issue')->value('is_active'))->toBeTrue();
    expect(CorrespondenceCategory::where('name', 'Third Party Matters')->value('is_active'))->toBeTrue();
    expect(CorrespondenceCategory::where('name', 'Outsource Issue')->value('is_active'))->toBeTrue();
})->group('seeders', 'correspondence');
