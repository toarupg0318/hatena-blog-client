<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns;

/**
 * @internal
 */
trait RecursiveSearchWithKeyValueTrait
{
    /**
     * Recursively searches a given multidimensional array for an array containing
     * a specific "searchKey", "searchValue" pair, and retrieves the value of a specific siblingKey at the same level.
     * If not found, returns null.
     *
     * @param array<int|string, mixed> $arrayToSearch
     * @param string $searchKey
     * @param string $searchValue
     * @param string $siblingKey
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
    public function recursiveSearchWithKeyValue(
        array $arrayToSearch,
        string $searchKey,
        string $searchValue,
        string $siblingKey
    ): string|null {
        foreach ($arrayToSearch as $itemToLex) {
            if (is_array($itemToLex)) {
                if (
                    array_key_exists($searchKey, $itemToLex) &&
                    $itemToLex[$searchKey] === $searchValue &&
                    array_key_exists($siblingKey, $itemToLex)
                ) {
                    return $itemToLex[$siblingKey];
                } else {
                    $return = $this->recursiveSearchWithKeyValue(
                        $itemToLex,
                        $searchKey,
                        $searchValue,
                        $siblingKey
                    );
                    if ($return !== null) {
                        return $return;
                    }
                }
            }
        }

        return null;
    }
}