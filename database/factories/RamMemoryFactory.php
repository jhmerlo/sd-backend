<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RamMemory>
 */
class RamMemoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'model' => $this->faker->words(4, true),
            'manufacturer' => $this->faker->company(),
            'functional' => $this->faker->boolean(),
            'size' => $this->faker->randomFloat(0, 1, 16),
            'clock' => $this->faker->randomFloat(1, 1, 5),
            'technology' => $this->faker->randomElement(['DDR3', 'DDR4'])
        ];
    }

    public function functional ()
    {
        return $this->state(function (array $attributes) {
            return [
                'functional' => true
            ];
        });
    }
}
