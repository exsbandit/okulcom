<?php

namespace App\Http\Requests\v1\Order;

use App\Rules\v1\ProductStockRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'products.*.quantity' => ['integer', 'required', 'min:1', new ProductStockRule($this->products)],
        ];
    }
}
