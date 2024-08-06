<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'burgers' => 'required|array',
            'burgers.*.id' => 'required|exists:burgers,id',
            'burgers.*.quantity' => 'required|integer|min:1',
            'addressLivraison' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'burgers.required' => 'Le champ des burgers est obligatoire.',
            'burgers.array' => 'Le champ des burgers doit être un tableau.',
            'burgers.*.id.required' => 'L\'ID du burger est obligatoire.',
            'burgers.*.id.exists' => 'L\'ID du burger doit être un ID valide dans la base de données.',
            'burgers.*.quantity.required' => 'La quantité du burger est obligatoire.',
            'burgers.*.quantity.integer' => 'La quantité du burger doit être un nombre entier.',
            'burgers.*.quantity.min' => 'La quantité du burger doit être d\'au moins 1.',
            'addressLivraison.required' => 'L\'adresse de livraison est obligatoire.',
            'addressLivraison.string' => 'L\'adresse de livraison doit être une chaîne de caractères.',
            'addressLivraison.max' => 'L\'adresse de livraison ne peut pas dépasser 255 caractères.',
        ];
    }
}
