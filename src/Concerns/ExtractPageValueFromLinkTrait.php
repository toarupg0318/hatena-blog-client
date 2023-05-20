<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns;

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;

/**
 * @internal
 */
trait ExtractPageValueFromLinkTrait
{
    /**
     * @param string $linkUrl
     *
     * @throws HatenaInvalidArgumentException
     * @example
     *   1780929531
     *      => 1780929531
     * @example
     *   https://blog.hatena.ne.jp/hoge0318/fuga0318.hatenablog.com/atom/entry?page=1780929531
     *      => 1780929531
     */
    private function extractPageValueFromLink(string $linkUrl): string
    {
        preg_match('/^(http|https)(:\/\/)(.*)(\?page=)(.*)$/u', $linkUrl, $matches);

        if (! empty($matches[5] ?? null)) {
            return $matches[5];
        }

        if ($matches === []) {
            return $linkUrl;
        }

        throw new HatenaInvalidArgumentException();
    }
}