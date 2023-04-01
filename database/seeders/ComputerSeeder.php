<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Computer;
use App\Models\Motherboard;

class ComputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Computer::factory()
            ->count(10)
            ->create();

        Computer::factory()
            ->has(Motherboard::factory()->count(1))
            ->step3()
            ->count(2)
            ->create();
    }
}
