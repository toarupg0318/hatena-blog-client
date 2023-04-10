<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns\DOM;

use Generator;
use IteratorAggregate;
use Stringable;

/**
 * @implements IteratorAggregate<HatenaDOMElement>
 */
class HatenaDOMDocument implements IteratorAggregate, Stringable
{
    // todo: static call to create

    /**
     * @param HatenaDOMElement[] $hatenaDOMElementList
     */
    public function __construct(
        private readonly array $hatenaDOMElementList = []
    ) {
    }

    /**
     * @param HatenaDOMElement $hatenaDOMElement
     * @return self<HatenaDOMElement>
     */
    public function append(HatenaDOMElement $hatenaDOMElement): self
    {
        $temp = $this->hatenaDOMElementList;
        $temp[] = $hatenaDOMElement;
        return new self($temp);
    }

    /**
     * @return Generator<int, HatenaDOMElement>
     */
    public function getIterator(): Generator
    {
        yield from $this->hatenaDOMElementList;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(
            separator: PHP_EOL,
            array: array_map(function (HatenaDOMElement $hatenaDOMElement) {
                return $hatenaDOMElement->__toString();
            }, $this->hatenaDOMElementList)
        );
    }
}