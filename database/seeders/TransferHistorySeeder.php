<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransferHistory;

class TransferHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransferHistory::factory()
            ->count(10)
            ->create();

        TransferHistory::factory()
            ->count(2)
            ->nullTarget()
            ->create();
    }
}
