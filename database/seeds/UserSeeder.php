<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i=0; $i<109; $i++)
        {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'nok' => $faker->name,
                'created_at'=> DB::raw('CURRENT_TIMESTAMP'),
                'updated_at'=> DB::raw('CURRENT_TIMESTAMP'),
                'email_verified_at' => DB::raw('CURRENT_TIMESTAMP'),
            ]);
        }
    }
}
