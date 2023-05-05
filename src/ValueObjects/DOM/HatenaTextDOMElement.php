<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

final class HatenaTextDOMElement extends HatenaDOMElement implements FootNoteAttachable
{
    public function __construct(
        private readonly string $text
    ) {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->text . PHP_EOL;
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
                subject: $this->text
            )
            . PHP_EOL;
    }
}