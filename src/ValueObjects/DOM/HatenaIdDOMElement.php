<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

final class HatenaIdDOMElement extends HatenaDOMElement
{
    public const OPTIONAL_TAGS = [
        'image',
        'detail',
        'detail:large',
    ];

    /**
     * @param string $hatenaId  hatena id to make link
     * @param value-of<self::OPTIONAL_TAGS>|null $optionalTag   'image' or 'detail' or 'detail:large'
     */
    public function __construct(
        private readonly string $hatenaId,
        private readonly string|null $optionalTag = null
    ) {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $optionalTagToEmbed = match (true) {
            $this->optionalTag === null
                => '',
            in_array($this->optionalTag, self::OPTIONAL_TAGS, true)
                => ":{$this->optionalTag}",
            default
                => '',
        };

        return 'id:' . $this->hatenaId . $optionalTagToEmbed
            . PHP_EOL;
    }
}