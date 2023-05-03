<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns;

/**
 * @internal
 */
trait HatenaArrayWalkRecursiveTrait
{
    /**
     * todo: 変数名の変更、HatenaHatenaGetListResponseのprivateメソッドにする
     * Recursively searches a given multidimensional array for an array containing
     * a specific key(1), value pair, and retrieves the value of a specific key(2) at the same level.
     * If not found, returns null.
     *
     * @param array<int|string, mixed> $arrayToLex
     * @param string $key1
     * @param string $value1
     * @param string $key2
     * @return string|null
     *
     * @example
     * ```
     *  param1:
     *   [
     *     'hoge' => [
     *          'fuga' => [
     *              ...
     *              'key1' => 'value1',
     *              'key2' => 'value2',
     *              ...
     *          ]
     *      ]
     *   ]
     *  param2: 'key1'
     *  param3: 'value1'
     *  param4: 'key2'
     *  return: 'value2'
     * ```
     */
    public function hatenaArrayWalkRecursive(
        array  $arrayToLex,
        string $key1,
        string $value1,
        string $key2
    ): string|null {
        foreach ($arrayToLex as $childArrayToLex) {
            if (
                is_array($childArrayToLex) &&
                array_key_exists($key1, $childArrayToLex)
            ) {
                $extractedValue1 = $childArrayToLex[$key1] ?? null;
                if ($extractedValue1 !== $value1) {
                    return $this->hatenaArrayWalkRecursive(
                        arrayToLex: $childArrayToLex,
                        key1: $key1,
                        value1: $value1,
                        key2: $key2
                    );
                }
                $value2 = $childArrayToLex[$key2] ?? null;
                return empty($value2)
                    ? null
                    : strval($value2);
            } else {
                if (! is_array($childArrayToLex)) {
                    continue;
                }
                return $this->hatenaArrayWalkRecursive(
                    arrayToLex: $childArrayToLex,
                    key1: $key1,
                    value1: $value1,
                    key2: $key2
                );
            }
        }

        return null;
    }
}