<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seeding users
        $this->call(UsersTableSeeder::class);

        // Seeding dummy clients
        $this->call(ClientsTableSeeder::class);

        // Seeding dummy Payments
        $this->call(PaymentsTableSeeder::class);
    }
}
