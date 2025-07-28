<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'country_id' => null, // Set to null or random country ID when countries table exists
            'password' => Hash::make('password'), // Default password
            'status' => 'Active',
            'remember_token' => null,
        ];
    }
}
