<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Stringable;

final class HatenaSuperPreDOMElement extends HatenaDOMElement
{
    public function __construct(
        private readonly string|null $value,

    ) {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return <<<HATENA
>||
$this->value
||<
HATENA;

    }
}