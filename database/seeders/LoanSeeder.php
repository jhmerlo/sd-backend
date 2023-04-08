<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\Computer;
use App\Models\Borrower;
use App\Models\User;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Loan::factory()
            ->count(3)
            ->loanableMonitor()
            ->create();

        Loan::factory()
            ->count(3)
            ->create();
    }
}
