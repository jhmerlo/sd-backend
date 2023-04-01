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
            'currentStep' => 2,
            'currentStepResponsibleId' => User::factory()
        ];
    }
}
