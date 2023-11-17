<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Player::where('account', 'test')->exists()) {
            Player::factory()->create([
                'account' => 'test',
                'displayName' => 'test123',
                'password' => password_hash('123456789', PASSWORD_DEFAULT),
                'email' => 'test@email.com',
                'status' => 'active',
                'balance' => '10000',
                'lastLoggedInIp' => '127.0.0.1',
                'lastLoggedInAt' => now(),
            ]);
        }
        
        Player::factory(1)->create();
    }
}
