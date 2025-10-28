<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\Compte;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            // Lie la transaction à un compte existant ou en crée un si nécessaire
            'compte_id' => Compte::factory(),

            'montant' => $this->faker->randomFloat(2, 10, 1000),

            'type' => $this->faker->randomElement(['debit', 'credit']),
        ];
    }
}
