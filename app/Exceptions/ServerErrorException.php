<?php

namespace App\Exceptions;

use Exception;

class ServerErrorException extends Exception
{
    public $validator;

    protected $message = '';

    protected $code = 500;

    public function __construct(string $message, int $code = 500)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render()
    {
        // return a json with desired format
        return response()->json([
            'success' => false,
            'message' => $this->message,
        ], $this->code);
    }
}
