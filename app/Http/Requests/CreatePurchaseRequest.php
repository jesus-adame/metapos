<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
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
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'location_id' => 'required|integer',
            'location_type' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'products.required' => 'Debes cargar al menos un producto.',
            'purchase_date.required' => 'Elijar una fecha.',
            'location_id.required' => 'Elija una ubicación.',
            'location_type.required' => 'Elija un tipo de ubicación.',
        ];
    }
}
