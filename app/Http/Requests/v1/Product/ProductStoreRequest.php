<?php

namespace App\Http\Requests\v1\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => ['integer', 'required', 'exists:categories,id'],
            'name' => ['string', 'required'],
            'description' => ['string', 'sometimes'],
            'price' => ['numeric', 'required'],
            'stock' => ['integer', 'required', 'min:1'],
        ];
    }
}
