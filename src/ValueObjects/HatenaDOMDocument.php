<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects;

use Countable;
use Generator;
use IteratorAggregate;
use Stringable;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\FootNoteAttachable;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaH3DOMElement;

/**
 * @implements IteratorAggregate<HatenaDOMElement>
 */
final class HatenaDOMDocument implements IteratorAggregate, Stringable, Countable
{
    /**
     * @param HatenaDOMElement[] $hatenaDOMElementList
     * @throws HatenaUnexpectedException
     */
    private function __construct(
        private readonly array $hatenaDOMElementList = []
    ) {
        foreach ($hatenaDOMElementList as $hatenaDOMElement) {
            if (! $hatenaDOMElement instanceof HatenaDOMElement) {
                throw new HatenaUnexpectedException();
            }
        }
    }

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * @param HatenaDOMElement $hatenaDOMElement
     * @return self<HatenaDOMElement>
     * @throws HatenaUnexpectedException
     */
    private function append(HatenaDOMElement $hatenaDOMElement): self {
        $temp = $this->hatenaDOMElementList;
        $temp[] = $hatenaDOMElement;
        return new self(
            hatenaDOMElementList: $temp
        );
    }

    /**
     * @param string $h3Value
     * @return self
     * @throws HatenaUnexpectedException
     */
    public function appendH3(string $h3Value): self
    {
        return $this->append(new HatenaH3DOMElement($h3Value));
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
                return ($hatenaDOMElement instanceof FootNoteAttachable)
                    ? $hatenaDOMElement->__toStringWithFootNote()
                    : $hatenaDOMElement->__toString();
            }, $this->hatenaDOMElementList)
        );
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->hatenaDOMElementList);
    }
}