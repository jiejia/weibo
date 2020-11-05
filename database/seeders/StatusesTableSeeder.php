<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use Carbon\Carbon;

class StatusesTableSeeder extends Seeder
{
    /**
     * @param Faker $faker
     * @version  2020-11-5 11:31
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function run(Faker $faker)
    {
        //
        for ($i = 1; $i <= 100; $i ++) {
            DB::table('statuses')->insert([
                'content' => $faker->paragraph(3, true),
                'user_id' => rand(1, 100),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }
    }
}
