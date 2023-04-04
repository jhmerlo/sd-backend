<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Processor>
 */
class ProcessorFactory extends Factory
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
            'clock' => $this->faker->randomFloat(2, 2, 4),
            'threads' => $this->faker->randomDigit(),
            'cache' => $this->faker->randomDigit()
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
