<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns\DOM;

final class HatenaTableDOMElement extends HatenaDOMElement
{
    /**
     * @param array{
     *     headers: array<int<0, max>, string|null>,
     *     lines: array<int<0, max>, array<int<0, max>, string|null>>
     * } $table
     */
    public function __construct(
        private readonly array $table
    ) {
    }

    // todo: table操作アクションs

    /**
     * @return string
     */
    public function __toString(): string
    {
        $headerPart = implode(
                '',
                array_map(function (string|null $headerItem) {
                    return '|*' . $headerItem;
                }, $this->table['headers'])
            ) . '|';

        /** @var string[] $lineParts */ // todo: delete
        $lineParts = [];
        foreach ($this->table['lines'] as $line) {
            $lineParts[] = implode(
                '',
                    array_map(function (string|null $lineItem) {
                        return '|' . $lineItem;
                    }, $line)
                ) . '|';
        }

        return implode(
            PHP_EOL,
            array_merge([$headerPart], $lineParts)
        ) . PHP_EOL;
    }
}