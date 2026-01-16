<?php

namespace Database\Factories;

use App\Models\Correspondence;
use App\Models\CorrespondenceCategory;
use App\Models\CorrespondencePriority;
use App\Models\CorrespondenceStatus;
use App\Models\Division;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Correspondence>
 */
class CorrespondenceFactory extends Factory
{
    protected $model = Correspondence::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['Receipt', 'Dispatch']),
            'receipt_no' => fake()->optional(0.5)->bothify('REC-####-???'),
            'dispatch_no' => fake()->optional(0.5)->bothify('DIS-####-???'),
            'letter_type_id' => LetterType::factory(),
            'category_id' => CorrespondenceCategory::factory(),
            'sender_name' => fake()->optional()->company(),
            'from_division_id' => Division::factory(),
            'to_division_id' => Division::factory(),
            'reference_number' => fake()->optional()->bothify('REF-####-???'),
            'letter_date' => fake()->date(),
            'received_date' => fake()->date(),
            'subject' => fake()->sentence(),
            'description' => fake()->optional()->paragraph(),
            'status_id' => CorrespondenceStatus::factory(),
            'priority_id' => CorrespondencePriority::factory(),
            'current_holder_id' => User::factory(),
            'created_by' => User::factory(),
        ];
    }

    public function receipt(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Receipt',
        ]);
    }

    public function dispatch(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'Dispatch',
        ]);
    }
}
