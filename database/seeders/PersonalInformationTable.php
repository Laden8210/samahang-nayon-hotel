<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalInformationTable extends Seeder
{

    public function run(): void
    {
       $faker = \Faker\Factory::create();
       foreach(range(1,10) as $index){
              DB::table('personal_information')->insert([
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'dob' => $faker->date,
                'age' => $faker->numberBetween(18, 60),
                'status' => $faker->randomElement(['active', 'inactive']),
                'created_at' => now()]);

       }
    }
}
