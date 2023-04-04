<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PowerSupply>
 */
class PowerSupplyFactory extends Factory
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
            'electric_power' => $this->faker->randomFloat(2, 300, 400),
            'voltage' => $this->faker->randomFloat(2, 18, 30)
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
