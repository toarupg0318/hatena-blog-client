<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Exceptions;

use Exception;
use Throwable;

final class HatenaHttpException extends Exception
{
    public const MESSAGE = 'Http connection to Hatena has failed.';

    public function __construct(
        string $message = self::MESSAGE,
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}