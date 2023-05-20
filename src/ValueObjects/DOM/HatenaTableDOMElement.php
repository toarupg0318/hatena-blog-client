<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;

final class HatenaTableDOMElement extends HatenaDOMElement
{
    /**
     * @param array{
     *     headers: array<int<0, max>, string|null>,
     *     lines: array<int<0, max>, array<int<0, max>, string|null>>
     * } $table
     *
     * @throws HatenaInvalidArgumentException
     */
    public function __construct(
        private readonly array $table
    ) {
        $headerRowCount = count($table['headers']);
        foreach ($table['lines'] as $line) {
            if (count($line) !== $headerRowCount) {
                throw new HatenaInvalidArgumentException('The number of row is invalid.');
            }
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $headerPart = implode(
                '',
                array_map(fn(string|null $headerItem): string => '|*' . $headerItem, $this->table['headers'])
            ) . '|';

        /** @var string[] $lineParts */
        $lineParts = [];
        foreach ($this->table['lines'] as $line) {
            $lineParts[] = implode(
                '',
                    array_map(fn(string|null $lineItem): string => '|' . $lineItem, $line)
                ) . '|';
        }

        return implode(
            PHP_EOL,
            array_merge([$headerPart], $lineParts)
        ) . PHP_EOL;
    }
}