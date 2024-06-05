<?php

namespace App\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserValidationException extends ValidationException
{
    public $validator;

    protected $code = 401;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function render()
    {
        // return a json with desired format
        return response()->json([
            'success' => false,
            'message' => $this->validator->errors()->first(),
            'errors' => $this->validator->errors(),
        ], $this->code);
    }
}
