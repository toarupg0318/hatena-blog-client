<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

final class HatenaBrDOMElement extends HatenaDOMElement
{
    public function __construct() {}

    /**
     * @return string
     */
    public function __toString(): string
    {
        return PHP_EOL . PHP_EOL;
    }
}