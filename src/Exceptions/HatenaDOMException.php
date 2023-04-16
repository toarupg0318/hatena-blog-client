<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Exceptions;

use LogicException;

abstract class HatenaDOMException extends LogicException
{
    public function __construct(
        $message = "",
        $code = 0,
        $previous = null
    ) {
        parent::__construct();
    }
}