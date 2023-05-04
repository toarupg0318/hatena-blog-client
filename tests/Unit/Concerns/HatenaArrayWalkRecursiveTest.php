<?php

use Toarupg0318\HatenaBlogClient\Concerns\RecursiveSearchWithKeyValueTrait;

$classUsingHatenaArrayWalkRecursiveTrait = new class() {
    use RecursiveSearchWithKeyValueTrait;
};

it(
    'tests RecursiveSearchWithKeyValueTrait performs correctly',
    function () use ($classUsingHatenaArrayWalkRecursiveTrait) {
        $dataToSearch = 'https://blog.hatena.ne.jp/foo0318/bar0318.hatenablog.com/atom/entry';
        $key1 = 'rel';
        $value1 = 'first';
        $key2 = 'href';
        $testData = [
            'link' => [
                [
                    '@attributes' => [
                        $key1 => $value1,
                        $key2 => $dataToSearch,
                    ]
                ],
            ],
            'title' => 'hogefugablog',
            'updated' => '2023-04-08T20:57:23+09:00',
            "author" => [
                'name' => 'piyo0318',
            ],
        ];

        expect($classUsingHatenaArrayWalkRecursiveTrait->recursiveSearchWithKeyValue(
            $testData,
            $key1,
            $value1,
            $key2
        ))
            ->toBe($dataToSearch)
            ->and($classUsingHatenaArrayWalkRecursiveTrait->recursiveSearchWithKeyValue(
                $testData,
                $key1 . 'foo',
                $value1,
                $key2
            ))
            ->toBeNull(message: 'key1 is invalid')
            ->and($classUsingHatenaArrayWalkRecursiveTrait->recursiveSearchWithKeyValue(
                $testData,
                $key1,
                $value1 . 'bar',
                $key2
            ))
            ->toBeNull('value1 is invalid')
            ->and($classUsingHatenaArrayWalkRecursiveTrait->recursiveSearchWithKeyValue(
                $testData,
                $key1,
                $value1,
                $key2 . 'piyo'
            ))
            ->toBeNull('key2 is invalid');

    }
);
