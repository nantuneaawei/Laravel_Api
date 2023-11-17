<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'account' => $this->faker->userName,
            'displayName' => $this->faker->name,
            'password' => password_hash('123456789', PASSWORD_DEFAULT),
            'email' => $this->faker->email,
            'status' => 'active',
            'balance' => '10000',
            'lastLoggedInIp' => $this->faker->ipv4,
            'lastLoggedInAt' => now(),
        ];
    }
}
