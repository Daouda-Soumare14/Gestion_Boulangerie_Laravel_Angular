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
            'user_id' => 'required|exists:users,id',
            'order_status' => 'required|in:validee,annulee',
            'delivery_status' => 'required|in:en_preparation,prete,en_livraison,livree',
            'payment_mode' => 'required|in:livraison,en ligne',
            'total' => 'required|numeric|min:0',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',

            'client_info' => 'required|array',
            'client_info.name' => 'required|string|max:255',
            'client_info.email' => 'required|email',
            'client_info.phone' => 'required|string|max:20',
            'client_info.address' => 'required|string|max:500',
        ];
    }
}
