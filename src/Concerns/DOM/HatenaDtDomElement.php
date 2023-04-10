<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns\DOM;

final class HatenaDtDomElement extends HatenaDOMElement
{
    public function __construct(
        private readonly string|null $title,
        private readonly string|null $description,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $separator = ':';

        return $separator . implode(
            separator: ':',
            array: [$this->title, $this->description]
        );
    }
}