<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable;

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
