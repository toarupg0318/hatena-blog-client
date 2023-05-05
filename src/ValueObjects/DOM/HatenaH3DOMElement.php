<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

final class HatenaH3DOMElement extends HatenaDOMElement implements FootNoteAttachable
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
        return '*' . $this->value . PHP_EOL;
    }

    /**
     * @param FootNote $footNote
     * @return string
     */
    public function attachFootNote(FootNote $footNote): string
    {
        return '*'
            . str_replace(
                search: $footNote->vocabulary,
                replace: $footNote->vocabulary . "(( {$footNote->description} ))",
                subject: $this->value
            )
            . PHP_EOL;
    }
}