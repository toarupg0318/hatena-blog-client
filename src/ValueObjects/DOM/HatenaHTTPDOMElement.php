<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

final class HatenaHTTPDOMElement extends HatenaDOMElement
{
    public const OPTIONAL_TAGS = ['title', 'barcode', 'image'];

    /**
     * @param value-of<self::OPTIONAL_TAGS>|null $optionalTag  'title' or 'barcode' or 'image'
     */
    public function __construct(
        private readonly string $httpUri,
        private readonly string|null $optionalTag = null
    ) {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $optionalTagToEmbed
            = ($this->optionalTag === null && in_array($this->optionalTag, self::OPTIONAL_TAGS, true))
                ? ''
                : ":{$this->optionalTag}";

        return '['
            . $this->httpUri . $optionalTagToEmbed
            . ']'
            . PHP_EOL;
    }
}