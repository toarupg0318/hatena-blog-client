<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

final class HatenaBlockQuoteDOMElement extends HatenaDOMElement
{
    public function __construct(
        private readonly string $value,
        private readonly string|null $url = null
    ) {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $prefix = (empty($this->url))
            ? '>>'
            : ">{$this->url}//>";
        $suffix = '<<';

        $rawContent = <<<HATENA
$prefix
$this->value
$suffix
HATENA;
        return htmlspecialchars($rawContent);
    }
}