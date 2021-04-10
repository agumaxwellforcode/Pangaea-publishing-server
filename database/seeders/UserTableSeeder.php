<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's clear the users table first
        User::truncate();

        $faker = \Faker\Factory::create();

        // let's hash the password
        $password = Hash::make('test');

        User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => $password,
        ]);

    }
}
