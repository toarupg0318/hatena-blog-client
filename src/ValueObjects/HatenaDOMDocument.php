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
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaHTTPDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaIdDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaLiDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaPreDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaReadMoreElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaStopPDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaSuperPreDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaSyntaxHighLightDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTableDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTableOfContentsDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTexDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTextDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTwitterDOMElement;

/**
 * @implements IteratorAggregate<HatenaDOMElement>
 */
final class HatenaDOMDocument implements IteratorAggregate, Stringable, Countable
{
    /**
     * @param HatenaDOMElement[] $hatenaDOMElementList
     *
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

    public static function create(): self
    {
        return new self();
    }

    /**
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
     * @param array<int<0,max>|string, mixed> $footNotes
     *
     * @throws HatenaInvalidArgumentException
     * @throws HatenaUnexpectedException
     */
    public function appendH3(string $h3Value, array $footNotes = []): self
    {
        $instantiatedFootNotes = [];
        foreach ($footNotes as $vocabulary => $description) {
            if (! is_string($vocabulary) || ! is_string($description)
            ) {
                continue;
            }
            $instantiatedFootNotes[] = new FootNote($vocabulary, $description);
        }

        return $this->append(new HatenaH3DOMElement($h3Value, $instantiatedFootNotes));
    }

    /**
     * @param array<int<0,max>|string, mixed> $footNotes
     *
     * @throws HatenaInvalidArgumentException
     * @throws HatenaUnexpectedException
     */
    public function appendH4(string $h4Value, array $footNotes = []): self
    {
        $instantiatedFootNotes = [];
        foreach ($footNotes as $vocabulary => $description) {
            if (! is_string($vocabulary) || ! is_string($description)
            ) {
                continue;
            }
            $instantiatedFootNotes[] = new FootNote($vocabulary, $description);
        }

        return $this->append(new HatenaH4DOMElement($h4Value, $instantiatedFootNotes));
    }

    /**
     * @param array<int<0,max>|string, mixed> $footNotes
     *
     * @throws HatenaInvalidArgumentException
     * @throws HatenaUnexpectedException
     */
    public function appendH5(string $h5Value, array $footNotes = []): self
    {
        $instantiatedFootNotes = [];
        foreach ($footNotes as $vocabulary => $description) {
            if (! is_string($vocabulary) || ! is_string($description)
            ) {
                continue;
            }
            $instantiatedFootNotes[] = new FootNote($vocabulary, $description);
        }

        return $this->append(new HatenaH5DOMElement($h5Value, $instantiatedFootNotes));
    }

    /**
     * @param string|null $url
     *
     * @throws HatenaUnexpectedException
     */
    public function appendBlockQuote(
        string $value,
        string $url = null
    ): self {
        return $this->append(new HatenaBlockQuoteDOMElement($value, $url));
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public function appendBr(): self
    {
        return $this->append(new HatenaBrDOMElement());
    }

    /**
     * @param value-of<HatenaHTTPDOMElement::OPTIONAL_TAGS>|null $optionTag
     *
     * @throws HatenaUnexpectedException
     */
    public function appendHttp(string $url, string $optionTag = null): self
    {
        return $this->append(new HatenaHTTPDOMElement($url, $optionTag));
    }

    /**
     * @param value-of<HatenaIdDOMElement::OPTIONAL_TAGS>|null $optionalTag
     *
     * @throws HatenaUnexpectedException
     */
    public function appendId(string $hatenaId, string|null $optionalTag = null): self
    {
        return $this->append(new HatenaIdDOMElement($hatenaId, $optionalTag));
    }

    /**
     * @param array{
     *     header: string|null,
     *     lines: string[]
     * } $table
     * @param HatenaLiDOMElement::SYNTAX_* $tag
     *
     * @throws HatenaUnexpectedException
     */
    public function appendLi(array $table, string $tag = '-'): self
    {
        return $this->append(new HatenaLiDOMElement($table, $tag));
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public function appendPre(string $value): self
    {
        return $this->append(new HatenaPreDOMElement($value));
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public function appendSuperPre(string $value): self
    {
        return $this->append(new HatenaSuperPreDOMElement($value));
    }

    /**
     * @param value-of<HatenaSyntaxHighLightDOMElement::LANGUAGES_TO_HANDLE> $language
     *
     * @throws HatenaUnexpectedException|HatenaInvalidArgumentException
     */
    public function appendSyntaxHighLight(string $language, string $script): self
    {
        return $this->append(new HatenaSyntaxHighLightDOMElement($language, $script));
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public function appendTex(string $texScript): self
    {
        return $this->append(new HatenaTexDOMElement($texScript));
    }

    /**
     * @param array<int<0,max>|string, mixed> $footNotes
     *
     * @throws HatenaUnexpectedException
     */
    public function appendText(string $text, array $footNotes = []): self
    {
        $instantiatedFootNotes = [];
        foreach ($footNotes as $vocabulary => $description) {
            if (! is_string($vocabulary) || ! is_string($description)
            ) {
                continue;
            }
            $instantiatedFootNotes[] = new FootNote($vocabulary, $description);
        }

        return $this
            ->append(hatenaDOMElement: new HatenaTextDOMElement(
                text: $text,
                footNotes: $instantiatedFootNotes
            ));
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public function appendTwitter(string $status): self
    {
        return $this->append(new HatenaTwitterDOMElement($status));
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public function appendReadMore(): self
    {
        return $this->append(new HatenaReadMoreElement());
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public function appendStopP(string $value): self
    {
        return $this->append(new HatenaStopPDOMElement($value));
    }

    /**
     * @param array{
     *     headers: array<int<0, max>, string|null>,
     *     lines: array<int<0, max>, array<int<0, max>, string|null>>
     * } $table
     *
     * @throws HatenaUnexpectedException
     */
    public function appendTable(array $table): self
    {
        return $this->append(new HatenaTableDOMElement($table));
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public function appendTableOfContents(): self
    {
        return $this->append(new HatenaTableOfContentsDOMElement());
    }

    /**
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
            array: array_map(fn(HatenaDOMElement $hatenaDOMElement): string => ($hatenaDOMElement instanceof FootNoteAttachable)
                ? $hatenaDOMElement->__toStringWithFootNote()
                : $hatenaDOMElement->__toString(), $this->hatenaDOMElementList)
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