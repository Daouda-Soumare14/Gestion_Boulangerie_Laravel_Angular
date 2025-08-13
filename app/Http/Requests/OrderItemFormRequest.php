<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        // On pourra ajouter un contrôle avec Spatie permissions ici
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'La commande est obligatoire.',
            'order_id.exists' => 'La commande sélectionnée est invalide.',
            'product_id.required' => 'Le produit est obligatoire.',
            'product_id.exists' => 'Le produit sélectionné est invalide.',
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un entier.',
            'quantity.min' => 'La quantité doit être au moins de 1.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être supérieur ou égal à 0.'
        ];
    }
}