<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un utilisateur "Jean"
        \App\Models\User::factory()->create([
            'name' => 'Jean Auto',
            'email' => 'jean@test.com',
            'password' => bcrypt('password'),
        ]);

        // Créer un utilisateur "Marc"
        \App\Models\User::factory()->create([
            'name' => 'Marc Garage',
            'email' => 'marc@test.com',
            'password' => bcrypt('password'),
        ]);
    }
}
