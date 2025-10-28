<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'nullable|string|max:20',
            'cni' => 'required|string|max:20|unique:clients,cni',
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
            'nom.required' => 'Le champ nom est obligatoire.',
            'nom.string' => 'Le champ nom doit être une chaîne de caractères.',
            'nom.max' => 'Le champ nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.email' => 'Le champ email doit être une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone.string' => 'Le champ téléphone doit être une chaîne de caractères.',
            'telephone.max' => 'Le champ téléphone ne peut pas dépasser 20 caractères.',
            'cni.required' => 'Le champ CNI est obligatoire.',
            'cni.string' => 'Le champ CNI doit être une chaîne de caractères.',
            'cni.max' => 'Le champ CNI ne peut pas dépasser 20 caractères.',
            'cni.unique' => 'Ce numéro CNI est déjà utilisé.',
        ];
    }
}