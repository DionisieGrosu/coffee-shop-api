<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CommonException extends Exception
{
    protected $message = '';

    protected $code = 500;

    public function __construct(string $message, int $code = 500, $context = [])
    {
        $this->message = $message;
        $this->code = $code;
        Log::channel('custom')->error($message, $context);
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
