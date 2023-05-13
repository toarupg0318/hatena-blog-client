<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;
use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

final class HatenaH5DOMElement extends HatenaDOMElement implements FootNoteAttachable
{
    /**
     * @param string $h5Value
     * @param FootNote[] $footNotes
     *
     * @throws HatenaInvalidArgumentException
     */
    public function __construct(
        private readonly string $h5Value,
        private readonly array $footNotes = []
    ) {
        foreach ($footNotes as $footNote) {
            if (! $footNote instanceof FootNote) {
                throw new HatenaInvalidArgumentException();
            }
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '***' . $this->h5Value . PHP_EOL;
    }

    /**
     * @param FootNote $footNote
     * @return self
     */
    public function attachFootNote(FootNote $footNote): self
    {
        return new self($this->h5Value, [
            ...$this->footNotes,
            $footNote
        ]);
    }

    /**
     * @return string
     */
    public function __toStringWithFootNote(): string
    {
        $patterns = array_map(
            fn (FootNote $footNote) => '/' . $footNote->vocabulary . '/u',
            $this->footNotes
        );
        $replacements = array_map(
            fn (FootNote $footNote) => $footNote->vocabulary . "(( {$footNote->description} ))",
            $this->footNotes
        );
        $temp = preg_replace(
            pattern: $patterns,
            replacement: $replacements,
            subject: $this->h5Value,
            limit: 1
        );

        return '***' . $temp . PHP_EOL;
    }
}