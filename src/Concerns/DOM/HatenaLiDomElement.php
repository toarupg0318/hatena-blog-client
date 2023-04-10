<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns\DOM;

final class HatenaLiDomElement extends HatenaDOMElement
{
    /**
     * @param array{
     *     header: string|null,
     *     lines: array<int<0, max>, string|null>
     * } $table
     * @param 'ol'|'ul' $tag
     */
    public function __construct(
        private readonly array $table,
        private readonly string $tag = 'ol'
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