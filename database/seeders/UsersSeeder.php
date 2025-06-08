<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'teacher',
            'email' => 'teacher@gmail.com',
            'password' => 'teacher',
            'email_verified_at' => now(),
        ]);
    }
}
