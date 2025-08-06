<?php

namespace App\Exceptions;

use Exception;

class InvalidEmailException extends Exception
{
    public function __construct(string $message = "Invalid Email", int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
