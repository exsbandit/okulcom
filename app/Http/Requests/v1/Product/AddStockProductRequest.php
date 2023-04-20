<?php

namespace App\Http\Requests\v1\Product;

use Illuminate\Foundation\Http\FormRequest;

class AddStockProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'products' => ['array', 'required'],
            'products.*.id' => ['integer', 'required', 'exists:products,id'],
            'products.*.quantity' => ['integer', 'required', 'min:2'],
        ];
    }
}
