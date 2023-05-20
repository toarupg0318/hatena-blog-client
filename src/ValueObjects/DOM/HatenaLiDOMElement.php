<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;
use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

final class HatenaLiDOMElement extends HatenaDOMElement implements FootNoteAttachable
{
    public const SYNTAX_MINUS = '-';
    public const SYNTAX_PLUS = '+';

    /** @var self::SYNTAX_* */
    private readonly string $tag;

    /**
     * @param array{
     *     header: string|null,
     *     lines: string[]
     * } $table
     * @param self::SYNTAX_* $tag
     * @param FootNote[] $footNotes
     *
     * @throws HatenaInvalidArgumentException
     */
    public function __construct(
        private readonly array $table,
        string $tag = '-',
        private readonly array $footNotes = []
    ) {
        $this->tag = in_array($tag, [self::SYNTAX_MINUS, self::SYNTAX_PLUS], true) ? $tag : '-';

        foreach ($footNotes as $footNote) {
            if (! $footNote instanceof FootNote) {
                throw new HatenaInvalidArgumentException();
            }
        }
    }

    /**
     *
     * @throws HatenaInvalidArgumentException
     */
    public function append(string $additionalLineValue): self
    {
        $lineValues = $this->table['lines'];
        $lineValues[] = $additionalLineValue;

        return new self(
            table: [
                'header' => $this->table['header'],
                'lines' => $lineValues
            ],
            tag: $this->tag
        );
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $return = $this->tag . $this->table['header'] . PHP_EOL;
        foreach ($this->table['lines'] as $lineValue) {
            $return .= $this->tag . $this->tag . $lineValue . PHP_EOL;
        }
        return $return;
    }

    /**
     * @param FootNote $footNote
     * @return FootNoteAttachable
     *
     * @throws HatenaInvalidArgumentException
     */
    public function attachFootNote(FootNote $footNote): FootNoteAttachable
    {
        return new self(
            table: [
                'header' => $this->table['header'],
                'lines' => $this->table['lines']
            ],
            tag: $this->tag,
            footNotes: [
                ...$this->footNotes,
                $footNote
            ]
        );
    }

    /**
     * @return string
     */
    public function __toStringWithFootNote(): string
    {
        $patterns = array_map(
            fn (FootNote $footNote): string => '/' . $footNote->vocabulary . '/u',
            $this->footNotes
        );
        $replacements = array_map(
            fn (FootNote $footNote): string => $footNote->vocabulary . "(( {$footNote->description} ))",
            $this->footNotes
        );

        $tempHeader = preg_replace(
            pattern: $patterns,
            replacement: $replacements,
            subject: $this->table['header'] ?? '',
            limit: 1
        );
        $tempLines = array_map(
            callback: fn(string $value): ?string => preg_replace(
                pattern: $patterns,
                replacement: $replacements,
                subject: $value,
                limit: 1
            ),
            array: $this->table['lines']
        );

        $return = $this->tag . $tempHeader . PHP_EOL;
        foreach ($tempLines as $tempLineValue) {
            $return .= $this->tag . $this->tag . $tempLineValue . PHP_EOL;
        }
        return $return;
    }
}