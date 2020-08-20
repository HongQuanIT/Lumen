<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'email' => 'admin@aegona.com',
            'password' => app('hash')->make('1234')
        ]);

        factory(App\User::class)->create([
            'email' => 'admin1@aegona.com',
            'password' => app('hash')->make('12345')
        ]);

        factory(App\User::class)->create([
            'email' => 'admin2@aegona.com',
            'password' => app('hash')->make('12346')
        ]);
    }
}