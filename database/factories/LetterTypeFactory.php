<?php

namespace Database\Factories;

use App\Models\LetterType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LetterType>
 */
class LetterTypeFactory extends Factory
{
    protected $model = LetterType::class;

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
            'requires_reply' => fake()->boolean(),
            'default_days_to_reply' => fake()->optional()->numberBetween(3, 30),
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }
}
