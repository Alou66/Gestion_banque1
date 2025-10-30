<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     schema="Compte",
 *     title="Compte",
 *     description="Modèle représentant un compte bancaire",
 *     @OA\Property(property="id", type="string", format="uuid", description="Identifiant unique du compte"),
 *     @OA\Property(property="numero", type="string", description="Numéro du compte bancaire"),
 *     @OA\Property(property="type", type="string", enum={"cheque", "epargne"}, description="Type de compte"),
 *     @OA\Property(property="solde", type="number", format="float", description="Solde du compte"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "bloque", "ferme"}, description="Statut du compte"),
 *     @OA\Property(property="client_id", type="string", format="uuid", description="Identifiant du client propriétaire"),
 *     @OA\Property(property="motif_blocage", type="string", nullable=true, description="Motif du blocage si applicable"),
 *     @OA\Property(property="supprime", type="boolean", description="Indique si le compte est supprimé"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de dernière modification"),
 *     @OA\Property(
 *         property="client",
 *         ref="#/components/schemas/Client",
 *         description="Informations du client propriétaire du compte"
 *     )
 * )
 */
class Compte extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'numero',
        'type',
        'solde',
        'statut',
        'client_id',
        'motif_blocage',
        'supprime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($compte) {
            if (empty($compte->numero)) {
                $compte->numero = 'CPT-' . strtoupper(Str::random(6));
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('nonSupprimes', function ($builder) {
            $builder->where('supprime', false)
                    ->whereIn('type', ['cheque', 'epargne'])
                    ->where('statut', 'actif');
        });
    }

    public function scopeNumero($query, $numero)
    {
        return $query->where('numero', 'like', '%' . $numero . '%');
    }

    public function scopeClient($query, $telephone)
    {
        return $query->whereHas('client', function ($q) use ($telephone) {
            $q->where('telephone', $telephone);
        });
    }
}
