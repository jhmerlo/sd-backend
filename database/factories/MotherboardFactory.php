<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Computer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Motherboard>
 */
class MotherboardFactory extends Factory
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
            'functional' => $this->faker->boolean()
        ];
    }
}
