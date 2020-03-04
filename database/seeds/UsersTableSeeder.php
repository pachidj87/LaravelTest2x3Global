<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $api_token = Str::random(80);

        User::create([
            'name' => '2x3 Test user',
            'email' => 'user@laraveltext2x3global.com',
            'password' => Hash::make('LaravelTest2x3Global'),
            'api_token' => hash('sha256', $api_token)
        ]);

        $this->command->warn(sprintf('Please copy and remember generated token to access test api. Token: %s', $api_token));
    }
}
