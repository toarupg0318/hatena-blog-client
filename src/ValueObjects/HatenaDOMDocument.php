<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects;

use Generator;
use IteratorAggregate;
use Stringable;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaH3DOMElement;

/**
 * @implements IteratorAggregate<HatenaDOMElement>
 */
final class HatenaDOMDocument implements IteratorAggregate, Stringable
{
    /**
     * @param HatenaDOMElement[] $hatenaDOMElementList
     * @param FootNote[] $footNotes
     * @throws HatenaUnexpectedException
     */
    private function __construct(
        private readonly array $hatenaDOMElementList = [],
        // @phpstan-ignore-next-line
        private readonly array $footNotes = []
    ) {
        foreach ($hatenaDOMElementList as $hatenaDOMElement) {
            if (! $hatenaDOMElement instanceof HatenaDOMElement) {
                throw new HatenaUnexpectedException();
            }
        }

        foreach ($footNotes as $footNote) {
            if (! $footNote instanceof FootNote) {
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
     * @param array<string, string>[] $footNotes
     * @return self<HatenaDOMElement>
     * @throws HatenaUnexpectedException
     */
    private function append(
        HatenaDOMElement $hatenaDOMElement,
        array $footNotes = []
    ): self {
        $temp = $this->hatenaDOMElementList;
        $temp[] = $hatenaDOMElement;
        return new self(
            hatenaDOMElementList: $temp,
            footNotes: array_map(
            // @phpstan-ignore-next-line todo: remove type error
                static function (string $vocabulary, string $description) {
                    return new FootNote($vocabulary, $description);
                },
                array_keys($footNotes),
                array_values($footNotes)
            ));
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
     * @param string $vocabulary
     * @param string $description
     * @return self
     *
     * @example
     * ```
     *  param1: 'ChatGPT'
     *  param2: 'アメリカのOpen AI社が開発した、人工知能（AI）を使ったチャットサービスのこと。'
     * ```
     *
     * @throws HatenaUnexpectedException
     */
    public function setFootNote(
        string $vocabulary,
        string $description
    ): self {
        return new self(
            hatenaDOMElementList: $this->hatenaDOMElementList,
            footNotes: [new FootNote($vocabulary, $description)]
        );
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