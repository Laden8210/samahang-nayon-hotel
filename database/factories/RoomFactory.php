<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        return [
            'RoomType' => $this->faker->randomElement(['Single', 'Double', 'Suite']),
            'RoomNumber' => $this->faker->numberBetween(100, 999),
            'Capacity' => $this->faker->numberBetween(1, 4),
            'RoomPrice' => $this->faker->randomFloat(2, 50, 500),
            'Status' => $this->faker->randomElement(['Available', 'Occupied']),
            'Description' => $this->faker->paragraph,
        ];
    }
}

