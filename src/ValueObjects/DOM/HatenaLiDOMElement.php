<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

final class HatenaLiDOMElement extends HatenaDOMElement
{
    public const SYNTAX_MINUS = '-';
    public const SYNTAX_PLUS = '+';

    /**
     * @param array{
     *     header: string|null,
     *     lines: array<int<0, max>, string|null>
     * } $table
     * @param self::SYNTAX_* $tag
     */
    public function __construct(
        private readonly array $table,
        private readonly string $tag = '-'
    ) {
    }

    /**
     * @param string|null $additionalLineValue
     * @return self
     */
    public function append(string|null $additionalLineValue): self
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
}