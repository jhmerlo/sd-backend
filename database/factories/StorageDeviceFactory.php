<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StorageDevice>
 */
class StorageDeviceFactory extends Factory
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
            'size' => $this->faker->randomFloat(0, 50, 1024),
            'type' => $this->faker->randomElement(['HD', 'SSD']),
            'connection_technology' => $this->faker->randomElement(['SATA', 'NVME'])
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
