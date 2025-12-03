<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'zero',
            'email' => '0@0.0',
            'password' => Hash::make('fufufafa'),
            'role' => 'master',
        ]);

        User::factory()->create([
            'name' => 'satu',
            'email' => '1@1.1',
            'password' => Hash::make('fufufafa'),
            'role' => 'writer',
        ]);
    }
}
