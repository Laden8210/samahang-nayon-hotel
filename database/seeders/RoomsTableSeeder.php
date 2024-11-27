<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Room;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Create 10 dummy rooms
        for ($i = 0; $i < 10; $i++) {
            Room::create([
                'description' => $faker->sentence(),
                'number' => $faker->unique()->numberBetween(100, 999),
                'type' => $faker->randomElement(['Single', 'Double', 'Suite']),
                'rate' => $faker->randomFloat(2, 50, 300),
                'status' => $faker->randomElement(['Available', 'Occupied', 'Under Maintenance']),
                'capacity' => $faker->numberBetween(1, 6),
            ]);
        }
    }
}
