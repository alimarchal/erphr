<?php

namespace Database\Factories;

use App\Models\CorrespondenceCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CorrespondenceCategory>
 */
class CorrespondenceCategoryFactory extends Factory
{
    protected $model = CorrespondenceCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'code' => fake()->unique()->lexify('???'),
            'parent_id' => null,
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }
}
