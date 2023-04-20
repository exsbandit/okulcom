<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
                'name' => ['string', 'sometimes', 'min:3, max:40'],
                'email' => ['email', 'required', 'email:rfc,dns'],
                'password' => ['string', 'required']
        ];
    }
}
