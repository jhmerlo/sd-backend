<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


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
        User::factory(9)->create();
        
        User::factory()->create([
            'name' => 'José Henrique',
            'email' => 'jose.h.sousa@edu.ufes.br',
            'password' => bcrypt('teste123')
        ]);

        // $this->call([
        //     DistributionCenterSeeder::class
        // ]);
    }
}
