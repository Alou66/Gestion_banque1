<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create one admin
        Client::factory()->admin()->create([
            'nom' => 'Admin System',
            'email' => 'admin@gestion-banque.com',
            'cni' => 'ADMIN00001',
        ]);

        // Create regular clients
        Client::factory(9)->create();
    }
}