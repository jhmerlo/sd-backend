<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserTestsHistory>
 */
class UserTestHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'auto_boot' => $this->faker->boolean(),
            'initialization' => $this->faker->boolean(),
            'shortcuts' => $this->faker->boolean(),
            'correct_date' => $this->faker->boolean(),
            'gsuite_performance' => $this->faker->words(3, true),
            'wine_performance' => $this->faker->words(3, true),
            'youtube_performance' => $this->faker->words(3, true),
            'responsible_id' => User::factory()
        ];
    }
}
