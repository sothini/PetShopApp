<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(9)->create();

        \App\Models\User::factory()->create([
            'first_name' => 'Admin',
            'last_name'=> 'User',
            'email' => 'admin@buckhill.co.uk',
            'password' => 'admin',
            'is_admin'=> 1
        ]);

        $this->call([ 
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            PaymentSeeder::class,
            OrderStatusSeeder::class,
         ]);
    }
}
