<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'customer_id' => 'nullable|exists:customers,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|decimal:0,4',
            'payment_methods' => 'nullable|array',
            'payment_methods.*.method' => 'nullable|in:cash,card,transfer',
            'payment_methods.*.amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El cliente es obligatorio.',
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
            'payment_methods.required' => 'Debes agregar al menos un método de pago.',
            'payment_methods.array' => 'El campo métodos de pago debe ser un array.',
            'payment_methods.*.method.required' => 'El método de pago es obligatorio.',
            'payment_methods.*.method.in' => 'El método de pago debe ser uno de los siguientes: efectivo, tarjeta de crédito, tarjeta de débito o transferencia.',
            'payment_methods.*.amount.required' => 'El monto del método de pago es obligatorio.',
            'payment_methods.*.amount.numeric' => 'El monto del método de pago debe ser un número.',
            'payment_methods.*.amount.min' => 'El monto del método de pago debe ser al menos 0.',
        ];
    }
}