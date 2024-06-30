<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class DifferentEmail implements Rule
{
    protected $oldEmail;

    public function __construct($oldEmail)
    {
        $this->oldEmail = $oldEmail;
    }

    public function passes($attribute, $value)
    {
        return $value !== $this->oldEmail;
    }

    public function message()
    {
        return 'The new email cannot be the same as the old email.';
    }
}