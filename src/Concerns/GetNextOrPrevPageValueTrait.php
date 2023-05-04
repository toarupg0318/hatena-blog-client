<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns;

use Exception;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidPageUrlException;

/**
 * @example
 *  'https://blog.hatena.ne.jp/hoge0318/fuga0318.hatenablog.com/atom/entry?page=1780929531'
 *   => '1780929531'
 * @example
 *  'page=1780929531'
 *   => '1780929531'
 */
trait GetNextOrPrevPageValueTrait
{
    /**
     * @throws HatenaInvalidPageUrlException
     */
    private function getNextOrPrevPageValue(string $rawUrlValue): string
    {
        try {
            preg_match('/^(.*)(page=)(.*)$/u', $rawUrlValue, $matches);

            $extractedValue = $matches[3] ?? null;
            if (is_string($extractedValue)) {
                return $extractedValue;
            }

            throw new HatenaInvalidPageUrlException();
        } catch (Exception $exception) {
            throw new HatenaInvalidPageUrlException(
                message: $exception->getMessage(),
                code: $exception->getCode(),
                previous: $exception
            );
        }
    }
}