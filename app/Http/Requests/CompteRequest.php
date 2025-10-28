<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompteRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Mettre true pour autoriser toutes les requêtes pour le moment
        return true;
    }

    /**
     * Règles de validation pour la création ou mise à jour d'un compte.
     */
    public function rules(): array
    {
        return [
            'intitule' => 'required|string|max:255',
            'solde' => 'required|numeric|min:0',
            'client_id' => 'required|exists:clients,id', // Assure qu'il existe un client
        ];
    }

    /**
     * Messages personnalisés (optionnel)
     */
    public function messages(): array
    {
        return [
            'intitule.required' => 'Le champ intitulé est obligatoire.',
            'solde.required' => 'Le solde est obligatoire.',
            'solde.numeric' => 'Le solde doit être un nombre.',
            'client_id.required' => 'Un client doit être associé au compte.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
        ];
    }
}
