<?php

namespace Database\Seeders;

use App\Models\Compte;
use Illuminate\Database\Seeder;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Compte::factory(30)->create();
    }
}