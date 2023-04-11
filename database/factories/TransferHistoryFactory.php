<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gpu;
use App\Models\Computer;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransferHistory>
 */
class TransferHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'source_id' => Computer::factory(),
            'target_id' => Computer::factory(),
            'transferable_id' => Gpu::factory(),
            'responsible_id' => User::factory(),
            'transferable_type' => 'App\\Models\\Gpu'
        ];
    }

    public function nullTarget()
    {
        return $this->state(function (array $attributes) {
            return [
                'target_id' => null
            ];
        });
    }
}
