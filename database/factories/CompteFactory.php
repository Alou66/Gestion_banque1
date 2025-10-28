<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compte>
 */
class CompteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => 'CPT-' . strtoupper(fake()->unique()->regexify('[A-Z0-9]{6}')),
            'type' => fake()->randomElement(['epargne', 'cheque']),
            'solde' => fake()->randomFloat(2, 0, 10000),
            'statut' => fake()->randomElement(['actif', 'bloque', 'ferme']),
            'client_id' => \App\Models\Client::factory(),
            'motif_blocage' => fake()->optional(0.2)->sentence(), // 20% chance of being blocked
            'supprime' => false,
        ];
    }
}
