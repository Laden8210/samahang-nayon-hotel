<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserAccount;

class UserAccountFactory extends Factory
{
    protected $model = UserAccount::class;

    public function definition()
    {
        return [
            'Username' => $this->faker->userName,
            'EmailAddress' => $this->faker->unique()->safeEmail,
            'Password' => bcrypt('password'),
            'AccountType' => $this->faker->randomElement(['guest', 'employee']),
            'Status' => 'active',
            'DateCreated' => $this->faker->date(),
            'TimeCreated' => $this->faker->time(),
        ];
    }
}
