<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Exceptions;

use Throwable;

final class HatenaInvalidPageUrlException extends HatenaException
{
    const MESSAGE = 'Passed page URL param is invalid.';

    public function __construct(
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct(self::MESSAGE, $code, $previous);
    }
}