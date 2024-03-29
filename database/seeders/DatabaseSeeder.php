<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Factories Imports
use App\Models\User;
use App\Models\Borrower;
use App\Models\Computer;
use App\Models\Motherboard;
use App\Models\Processor;
use App\Models\PowerSupply;
use App\Models\StorageDevice;
use App\Models\RamMemory;
use App\Models\Monitor;
use App\Models\Gpu;
use App\Models\Loan;
use App\Models\Comment;
use App\Models\MaintenanceHistory;
use App\Models\UserTestHistory;
use App\Models\TransferHistory;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //main admin account
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@sd.com',
            'institutional_id' => '3849231091',
            'telephone' => '(27) 99340-0493',
            'email_verified_at' => now(),
            'license' => 'active',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);

        //main maintenance account
        DB::table('users')->insert([
            'name' => 'Responsável',
            'email' => 'manutencao@sd.com',
            'institutional_id' => '3849231021',
            'telephone' => '(27) 98370-0201',
            'email_verified_at' => now(),
            'license' => 'active',
            'role' => 'maintenance',
            'password' => Hash::make('password')
        ]);

        $this->call([
            UserSeeder::class,
            ComputerSeeder::class,
            MotherboardSeeder::class,
            ProcessorSeeder::class,
            PowerSupplySeeder::class,
            StorageDeviceSeeder::class,
            RamMemorySeeder::class,
            MonitorSeeder::class,
            GpuSeeder::class,
            BorrowerSeeder::class,
            LoanSeeder::class,
            CommentSeeder::class,
            MaintenanceHistorySeeder::class,
            UserTestHistorySeeder::class,
            TransferHistorySeeder::class
        ]);
    }
}
