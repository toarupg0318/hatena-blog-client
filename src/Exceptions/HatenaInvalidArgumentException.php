<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Exceptions;

use Throwable;

final class HatenaInvalidArgumentException extends HatenaException
{
    public const MESSAGE = 'Passed argument is invalid.';

    public function __construct(
        string $message = self::MESSAGE,
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}