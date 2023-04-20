<?php

namespace App\Rules\v1;

use Closure;
use Illuminate\Contracts\Validation\Rule;


class PhoneNumberRule implements Rule
{

    public string $ruleMessage;

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strlen($value) < 12) {
            return true;
        } else if (strlen($value) == 12 && substr($value, 0, 1) == '+') {
            return true;
        } else {
            $this->ruleMessage = 'phone.number.type.error';
            return false;
        }
    }

    /**
     * @return array|mixed|string
     */
    public function message()
    {
        return $this->ruleMessage;
    }
}
