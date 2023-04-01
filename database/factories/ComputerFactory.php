<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Computer>
 */
class ComputerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['desktop', 'notebook']),
            'description' => $this->faker->words(4, true),
            'manufacturer' => $this->faker->company(),
            'sanitized' => $this->faker->boolean(),
            'functional' => $this->faker->boolean(),
            'current_step' => 2,
            'current_step_responsible_id' => User::factory()
        ];
    }

    public function step3()
    {
        return $this->state(function (array $attributes) {
            return [
                'current_step' => 3,
            ];
        });
    }
}
