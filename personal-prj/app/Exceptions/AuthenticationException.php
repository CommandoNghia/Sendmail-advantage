<?php

namespace App\Exceptions;

use Throwable;

class AuthenticationException extends CustomException
{
    /**
     * AuthenticationException constructor.
     *
     * @param string $message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(string $message = "Unauthenticated.", int $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
