<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Exceptions;

use Throwable;

final class HatenaUnexpectedException extends HatenaException
{
    const MESSAGE = 'Something unexpected has occurred in Hatena api process.';

    public function __construct(
        string $message = self::MESSAGE,
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}