<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns\DOM;

final class HatenaH5DomElement extends HatenaDOMElement
{
    public function __construct(
        private readonly string $value
    ) {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '***' . $this->value . PHP_EOL;
    }
}