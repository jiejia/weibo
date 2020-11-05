<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
Use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * @param Faker $faker
     * @version  2020-11-5 11:48
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function run(Faker $faker)
    {
        //
        for ($i = 1; $i <= 100; $i ++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' =>$faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'activated' => true,
            ]);
        }
    }
}
