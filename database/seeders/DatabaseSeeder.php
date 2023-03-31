<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Factories Imports
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class
        ]);

        //main admin account
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@sd.com',
            'institutionalId' => '3849231091',
            'telephone' => '(27) 99340-0493',
            'email_verified_at' => now(),
            'license' => 'active',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        //main maintenance account
        DB::table('users')->insert([
            'name' => 'Responsável',
            'email' => 'manutencao@sd.com',
            'institutionalId' => '3849231021',
            'telephone' => '(27) 98370-0201',
            'email_verified_at' => now(),
            'license' => 'active',
            'role' => 'maintenance',
            'password' => Hash::make('password'),
        ]);
    }
}
