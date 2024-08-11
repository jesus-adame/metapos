<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entry,exit',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'location_id' => 'required|integer',
            'location_type' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Elija un producto.',
            'type.required' => 'El tipo de transacción es obligatorio.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'transaction_date.required' => 'La fecha de transacción es requerida.',
            'description.required' => 'Debes agregar un motivo.',
            'location_id.required' => 'Elija una ubicación.',
            'location_type.required' => 'Elija un tipo de ubicación.',
        ];
    }
}
