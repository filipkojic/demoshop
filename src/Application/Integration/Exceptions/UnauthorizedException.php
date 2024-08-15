<?php

namespace Application\Integration\Exceptions;

use Exception;

/**
 * Class UnauthorizedException
 *
 * Custom exception class to handle unauthorized access.
 */
class UnauthorizedException extends Exception
{
    /**
     * UnauthorizedException constructor.
     *
     * @param string $message The error message.
     * @param int $code The error code.
     * @param Exception|null $previous The previous exception.
     */
    public function __construct(string $message = "Unauthorized access", int $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
