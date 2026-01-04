<?php

namespace Database\Factories;

use App\Models\CorrespondencePriority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CorrespondencePriority>
 */
class CorrespondencePriorityFactory extends Factory
{
    protected $model = CorrespondencePriority::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'code' => fake()->unique()->lexify('???'),
            'color' => fake()->hexColor(),
            'sla_hours' => fake()->optional()->numberBetween(8, 168),
            'sequence' => fake()->numberBetween(1, 4),
            'is_active' => true,
        ];
    }
}
