<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;
use App\Models\UserAccount;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        return [
            'FirstName' => $this->faker->firstName,
            'LastName' => $this->faker->lastName,
            'MiddleName' => $this->faker->lastName,
            'Position' => $this->faker->jobTitle,
            'Status' => 'Active',
            'ContactNumber' => '09123456789',
            'Gender' => $this->faker->randomElement(['Male', 'Female']),
            'Birthdate' => $this->faker->date(),
            'Street' => $this->faker->streetAddress,
            'City' => $this->faker->city,
            'Province' => $this->faker->state,
            'EmailAddress' => $this->faker->unique()->safeEmail,

        ];
    }
}
