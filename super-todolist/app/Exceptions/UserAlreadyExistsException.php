<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyExistsException extends Exception
{
    public function __construct(string $message = "Account already exists", int $code = 409)
    {
        parent::__construct($message, $code);
    }
}
