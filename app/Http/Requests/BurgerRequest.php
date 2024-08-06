<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BurgerRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'price' => ['required', 'numeric', 'min:1'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'archived' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'Le champ name est requis, doit être une chaîne de caractères, d\'une longueur minimale de 2 caractères et maximale de 255 caractères.',
            'price' => 'Le champ price est requis, doit être un nombre, et doit être supérieur ou égal à 0.',
            'quantity' => 'Le champ quantité est requis, doit être un nombre, et doit être supérieur ou égal à 0.',
            'image' => 'Le champ image est optionnel. Si fourni, il doit être un fichier image de type jpg, jpeg ou png, et ne doit pas dépasser 2 Mo.',
            'description' => 'Le champ description est optionnel, doit être une chaîne de caractères, et ne doit pas dépasser 1000 caractères.',
            'archived' => 'Le champ archived est optionnel et doit être un booléen (vrai ou faux).',
        ];
    }

}
