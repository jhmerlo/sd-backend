<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Computer;
use App\Models\Motherboard;
use App\Models\Processor;
use App\Models\PowerSupply;
use App\Models\StorageDevice;
use App\Models\RamMemory;
use App\Models\Monitor;
use App\Models\Gpu;

use App\Models\MaintenanceHistory;
use App\Models\UserTestHistory;

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
            ->has(Motherboard::factory()->count(1)->functional())
            ->has(Processor::factory()->count(1)->functional())
            ->has(PowerSupply::factory()->count(1)->functional())
            ->has(StorageDevice::factory()->count(2)->functional(1))
            ->has(RamMemory::factory()->count(2)->functional(1))
            ->has(Monitor::factory()->count(2)->functional(1))
            ->has(Gpu::factory()->count(2)->functional(1))
            ->has(MaintenanceHistory::factory()->count(2))
            ->has(UserTestHistory::factory()->count(1))
            ->step3()
            ->count(2)
            ->create();
    }
}
