<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Permission;

class CreatePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows(Permission::CREATE_PURCHASES);
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
            'applicated_at' => 'required|date',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'location_id' => 'required|integer',
            'location_type' => 'required|string',
            'update_cash_register' => 'boolean',
            'payment_methods' => 'nullable|array',
            'payment_methods.*.method' => 'nullable|in:cash,card,transfer',
            'payment_methods.*.amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'products.required' => 'Debes cargar al menos un producto.',
            'applicated_at.required' => 'Elijar una fecha.',
            'location_id.required' => 'Elija una ubicación.',
            'location_type.required' => 'Elija un tipo de ubicación.',
        ];
    }
}
