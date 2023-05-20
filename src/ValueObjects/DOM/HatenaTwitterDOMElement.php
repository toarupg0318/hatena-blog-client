<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;

final class HatenaTwitterDOMElement extends HatenaDOMElement
{
    /**
     * "status" means something like tweet id in Twitter.
     * For example, The status of "https://twitter.com/toarupg0318/status/1640997727260794882"
     * is "1640997727260794882"
     */
    public function __construct(
        public readonly string $status
    ) {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "twitter:{$this->status}:tweet" . PHP_EOL;
    }

    /**
     * @throws HatenaUnexpectedException
     */
    public static function createFromHttpsValue(string $httpsValue): self
    {
        preg_match('/^(https:\/\/twitter\.com\/)(.*)(\/status\/)(.*)$/u', $httpsValue, $matches);
        if (array_key_exists(4, $matches) && is_string($matches[4])) {
            return new self($matches[4]);
        }

        throw new HatenaUnexpectedException('Twitter URL is invalid.');
    }
}