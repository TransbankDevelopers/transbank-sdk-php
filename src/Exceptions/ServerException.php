<?php

namespace Transbank\Sdk\Exceptions;

use RuntimeException;

class ServerException extends RuntimeException implements TransbankException
{
    use HandlesException;

    /**
     * The log level to report to the app.
     *
     * @var string
     */
    public const LOG_LEVEL = LOG_CRIT;
}
