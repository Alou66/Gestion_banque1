<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'intitule' => 'required|string|max:255',
            'solde' => 'required|numeric|min:0',
            'client_id' => 'required|exists:clients,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'intitule.required' => 'Le champ intitulé est obligatoire.',
            'intitule.string' => 'Le champ intitulé doit être une chaîne de caractères.',
            'intitule.max' => 'Le champ intitulé ne peut pas dépasser 255 caractères.',
            'solde.required' => 'Le champ solde est obligatoire.',
            'solde.numeric' => 'Le champ solde doit être un nombre.',
            'solde.min' => 'Le solde ne peut pas être négatif.',
            'client_id.required' => 'Le champ client est obligatoire.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
        ];
    }
}