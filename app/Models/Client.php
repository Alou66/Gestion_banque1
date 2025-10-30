<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @OA\Schema(
 *     schema="Client",
 *     title="Client",
 *     description="Modèle représentant un client bancaire",
 *     @OA\Property(property="id", type="string", format="uuid", description="Identifiant unique du client"),
 *     @OA\Property(property="nom", type="string", description="Nom complet du client"),
 *     @OA\Property(property="email", type="string", format="email", description="Adresse email du client"),
 *     @OA\Property(property="telephone", type="string", description="Numéro de téléphone du client"),
 *     @OA\Property(property="cni", type="string", description="Numéro de carte d'identité nationale"),
 *     @OA\Property(property="role", type="string", enum={"admin", "client"}, description="Rôle du client (admin ou client)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de dernière modification")
 * )
 */
class Client extends Model implements Authenticatable
{
    use HasFactory, HasUuids, HasApiTokens;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'cni',
        'role',
    ];

    public function comptes(): HasMany
    {
        return $this->hasMany(Compte::class, 'client_id');
    }

    /**
     * Check if the client is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the client is a regular client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Get the key name for Passport.
     */
    public function getKeyName()
    {
        return 'id';
    }

    /**
     * Get the key type for Passport.
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        // Clients don't have passwords, return null
        return null;
    }

    /**
     * Get the token value for the "remember me" session.
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     */
    public function setRememberToken($value)
    {
        // Not implemented for clients
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return null;
    }
}
