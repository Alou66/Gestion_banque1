<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Transaction",
 *     title="Transaction",
 *     description="Modèle représentant une transaction bancaire",
 *     @OA\Property(property="id", type="string", format="uuid", description="Identifiant unique de la transaction"),
 *     @OA\Property(property="compte_id", type="string", format="uuid", description="Identifiant du compte associé"),
 *     @OA\Property(property="montant", type="number", format="float", description="Montant de la transaction"),
 *     @OA\Property(property="type", type="string", enum={"depot", "retrait", "transfert"}, description="Type de transaction"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de dernière modification"),
 *     @OA\Property(
 *         property="compte",
 *         ref="#/components/schemas/Compte",
 *         description="Informations du compte associé à la transaction"
 *     )
 * )
 */
class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'compte_id',
        'montant',
        'type',
    ];

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }
}
