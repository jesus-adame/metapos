<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Permission;

class StoreQuotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows(Permission::CREATE_SALES);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'wholesale' => 'required|boolean',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|decimal:0,4',
            'products.*.tax' => 'required|decimal:0,2',
            'discount' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El cliente es obligatorio.',
            'wholesale.required' => 'El precio mayorista es obligatorio.',
            'customer_id.exists' => 'El cliente no existe.',
            'products.required' => 'Debes agregar al menos un producto.',
            'products.array' => 'El campo productos debe ser un array.',
            'products.*.id.required' => 'El producto es obligatorio.',
            'products.*.id.exists' => 'El producto no existe.',
            'products.*.quantity.required' => 'La cantidad del producto es obligatoria.',
            'products.*.quantity.integer' => 'La cantidad del producto debe ser un número entero.',
            'products.*.quantity.min' => 'La cantidad del producto debe ser al menos 1.',
            'products.*.price.required' => 'El precio del producto es obligatorio.',
            'products.*.price.regex' => 'El precio del producto debe tener un formato válido (hasta 4 decimales).',
            'products.*.tax.required' => 'Los impuestos son obligatorios.',
        ];
    }
}
