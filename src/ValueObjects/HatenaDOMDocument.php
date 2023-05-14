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
     *
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
     *
     * @throws HatenaUnexpectedException|HatenaInvalidArgumentException
     */
    public function appendH3(string $h3Value): self
    {
        return $this->append(new HatenaH3DOMElement($h3Value));
    }

    /**
     * @param string $h4Value
     * @return self
     *
     * @throws HatenaInvalidArgumentException|HatenaUnexpectedException
     */
    public function appendH4(string $h4Value): self
    {
        return $this->append(new HatenaH4DOMElement($h4Value));
    }

    /**
     * @param string $h5Value
     * @return self
     *
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
     * @return self
     *
     * @throws HatenaUnexpectedException
     */
    public function appendBr(): self
    {
        return $this->append(new HatenaBrDOMElement());
    }

    /**
     * @param string $url
     * @param value-of<HatenaHTTPDOMElement::OPTIONAL_TAGS>|null $optionTag
     * @return self
     *
     * @throws HatenaUnexpectedException
     */
    public function appendHttp(string $url, string $optionTag = null): self
    {
        return $this->append(new HatenaHTTPDOMElement($url, $optionTag));
    }

    /**
     * @param string $hatenaId
     * @param value-of<HatenaIdDOMElement::OPTIONAL_TAGS>|null $optionalTag
     * @return self
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
     * @param string $value
     * @return self
     *
     * @throws HatenaUnexpectedException
     */
    public function appendPre(string $value): self
    {
        return $this->append(new HatenaPreDOMElement($value));
    }

    /**
     * @param string $value
     * @return self
     *
     * @throws HatenaUnexpectedException
     */
    public function appendSuperPre(string $value): self
    {
        return $this->append(new HatenaSuperPreDOMElement($value));
    }

    /**
     * @param value-of<HatenaSyntaxHighLightDOMElement::LANGUAGES_TO_HANDLE> $language
     * @param string $script
     * @return self
     *
     * @throws HatenaUnexpectedException|HatenaInvalidArgumentException
     */
    public function appendSyntaxHighLight(string $language, string $script): self
    {
        return $this->append(new HatenaSyntaxHighLightDOMElement($language, $script));
    }

    /**
     * @param string $texScript
     * @return self
     *
     * @throws HatenaUnexpectedException
     */
    public function appendTex(string $texScript): self
    {
        return $this->append(new HatenaTexDOMElement($texScript));
    }

    /**
     * @param string $text
     * @return self
     *
     * @throws HatenaUnexpectedException
     */
    public function appendText(string $text): self
    {
        return $this->append(new HatenaTextDOMElement($text));
    }

    /**
     * @param string $status
     * @return self
     *
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
     * @param string $value
     * @return self
     *
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
     * @return self
     *
     * @throws HatenaUnexpectedException
     */
    public function appendTableOfContents(): self
    {
        return $this->append(new HatenaTableOfContentsDOMElement());
    }

    /**
     * @param string $title
     * @param string $description
     * @return self
     *
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