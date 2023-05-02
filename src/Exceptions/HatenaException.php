<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Exceptions;

use Exception;
use Throwable;

abstract class HatenaException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}