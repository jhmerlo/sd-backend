<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Borrower;
use App\Models\Computer;
use App\Models\Monitor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'start_date' => now(),
            'end_date' => now(),
            'return_date' => now(),
            'responsible_id' => User::factory(),
            'borrower_id' => Borrower::factory(),
            'loanable_id' => Computer::factory(),
            'loanable_type' => 'App\\Models\\Computer'
        ];
    }


    public function loanableMonitor()
    {
        return $this->state(function (array $attributes) {
            return [
                'loanable_id' => Monitor::factory(),
                'loanable_type' => 'App\\Models\\Monitor'
            ];
        });
    }
}
