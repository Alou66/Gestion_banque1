<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numeroCompte' => $this->numero,
            'titulaire' => $this->client->nom,
            'type' => $this->type,
            'solde' => $this->solde,
            'devise' => 'XOF', // Assuming CFA Franc
            'dateCreation' => $this->created_at->format('Y-m-d'),
            'statut' => $this->statut,
            'motifBlocage' => $this->motif_blocage,
            'metadata' => [
                'derniereModification' => $this->updated_at->format('Y-m-d H:i:s'),
                'version' => '1.0',
            ],
        ];
    }
}