<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;
use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

final class HatenaH4DOMElement extends HatenaDOMElement implements FootNoteAttachable
{
    /**
     * @param string $h4Value
     * @param FootNote[] $footNotes
     */
    public function __construct(
        private readonly string $h4Value,
        private readonly array $footNotes = []
    ) {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '**' . $this->h4Value . PHP_EOL;
    }

    /**
     * @param FootNote $footNote
     * @return self
     */
    public function attachFootNote(FootNote $footNote): self
    {
        return new self($this->h4Value, [
            ...$this->footNotes,
            $footNote
        ]);
    }

    /**
     * @return string
     * @throws HatenaUnexpectedException
     */
    public function __toStringWithFootNote(): string
    {
        $temp = $this->h4Value;
        foreach ($this->footNotes as $footNote) {
            if (! is_string($temp)) {
                throw new HatenaUnexpectedException();
            }
            $temp = preg_replace(
                pattern: '/' . $footNote->vocabulary . '/u',
                replacement: $footNote->vocabulary . "(( {$footNote->description} ))",
                subject: $temp,
                limit: 1
            );
        }

        return '*' . $temp . PHP_EOL;
    }
}