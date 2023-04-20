<?php

namespace App\Http\Requests\v1\UserDetail;

use App\Rules\v1\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class UserDetailStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // phone number Exp. +15005550010
        return [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'phone_number' => [
                'required',
                'regex:/^\+\d{11}$/',
                new PhoneNumberRule()
            ],
        ];
    }
}
