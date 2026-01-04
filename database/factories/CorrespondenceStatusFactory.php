<?php

namespace Database\Factories;

use App\Models\CorrespondenceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CorrespondenceStatus>
 */
class CorrespondenceStatusFactory extends Factory
{
    protected $model = CorrespondenceStatus::class;

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
            'type' => fake()->randomElement(['Receipt', 'Dispatch', 'Both']),
            'is_initial' => false,
            'is_final' => false,
            'sequence' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }

    public function initial(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_initial' => true,
        ]);
    }

    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_final' => true,
        ]);
    }
}
