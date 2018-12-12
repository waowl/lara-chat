<?php

use App\User;
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
        factory(User::class, 3)->create();
        factory(User::class)->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('111111'),
            'remember_token' => str_random(10),
        ]);
        factory(User::class)->create([
            'name' => 'user',
            'email' => 'user@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('111111'),
            'remember_token' => str_random(10),
        ]);
    }
}
