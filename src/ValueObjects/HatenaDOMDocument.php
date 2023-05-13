<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects;

use Countable;
use Generator;
use IteratorAggregate;
use Stringable;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\FootNoteAttachable;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaBlockQuoteDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaBrDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDtDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaH3DOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaH4DOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaH5DOMElement;

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
     * @throws HatenaUnexpectedException|HatenaInvalidArgumentException
     */
    public function appendH3(string $h3Value): self
    {
        return $this->append(new HatenaH3DOMElement($h3Value));
    }

    /**
     * @param string $h4Value
     * @return self
     * @throws HatenaInvalidArgumentException|HatenaUnexpectedException
     */
    public function appendH4(string $h4Value): self
    {
        return $this->append(new HatenaH4DOMElement($h4Value));
    }

    /**
     * @param string $h5Value
     * @return self
     * @throws HatenaUnexpectedException|HatenaInvalidArgumentException
     */
    public function appendH5(string $h5Value): self
    {
        return $this->append(new HatenaH5DOMElement($h5Value));
    }

    /**
     * @param string $value
     * @param string|null $url
     * @return self
     * @throws HatenaUnexpectedException
     */
    public function appendBlockQuote(
        string $value,
        string $url = null
    ): self {
        return $this->append(new HatenaBlockQuoteDOMElement($value, $url));
    }

    /**
     * @return self
     * @throws HatenaUnexpectedException
     */
    public function appendBr(): self
    {
        return $this->append(new HatenaBrDOMElement());
    }

    /**
     * @param string $title
     * @param string $description
     * @return self
     * @throws HatenaUnexpectedException
     */
    public function appendDt(
        string $title,
        string $description
    ): self {
        return $this->append(new HatenaDtDOMElement($title, $description));
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