<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Concerns\HasUuids;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Support\Str;

// class Compte extends Model
// {
//     use HasUuids;

//     protected $keyType = 'string';
//     public $incrementing = false;

//     protected $fillable = [
//         'numero',
//         'type',
//         'solde',
//         'statut',
//         'client_id',
//         'motif_blocage',
//         'supprime',
//     ];

//     protected static function boot()
//     {
//         parent::boot();

//         static::creating(function ($compte) {
//             if (empty($compte->numero)) {
//                 $compte->numero = 'CPT-' . strtoupper(Str::random(6));
//             }
//         });
//     }

//     public function client(): BelongsTo
//     {
//         return $this->belongsTo(Client::class);
//     }

//     public function transactions()
//     {
//         return $this->hasMany(Transaction::class);
//     }

//     protected static function booted()
//     {
//         static::addGlobalScope('nonSupprimes', function ($builder) {
//             $builder->where('supprime', false)
//                     ->whereIn('type', ['cheque', 'epargne'])
//                     ->where('statut', 'actif');
//         });
//     }

//     public function scopeNumero($query, $numero)
//     {
//         return $query->where('numero', 'like', '%' . $numero . '%');
//     }

//     public function scopeClient($query, $telephone)
//     {
//         return $query->whereHas('client', function ($q) use ($telephone) {
//             $q->where('telephone', $telephone);
//         });
//     }
// }




namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // ← Ajouter ceci
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Compte extends Model
{
    use HasFactory; // ← Ajouter ceci
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
