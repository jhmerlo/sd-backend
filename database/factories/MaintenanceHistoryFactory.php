<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceHistory>
 */
class MaintenanceHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'software_installation' => $this->faker->words(3, true),
            'operational_system_installation' => $this->faker->words(3, true),
            'formatting' => $this->faker->words(3, true),
            'battery_change' => $this->faker->words(3, true),
            'suction' => $this->faker->words(3, true),
            'other' => $this->faker->words(3, true),
            'responsible_id' => User::factory()
        ];
    }
}
