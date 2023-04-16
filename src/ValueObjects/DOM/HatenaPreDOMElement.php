<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

final class HatenaPreDOMElement extends HatenaDOMElement
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
        return <<<HATENA
>|
$this->value
|<
HATENA;
    }
}