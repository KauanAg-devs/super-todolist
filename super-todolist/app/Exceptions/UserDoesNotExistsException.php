<?php

namespace App\Exceptions;

use Exception;

class UserDoesNotExistsException extends Exception
{
    public function __construct(string $message = "Account does not exists", int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
