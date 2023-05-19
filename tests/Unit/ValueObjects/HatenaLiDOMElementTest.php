<?php

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaLiDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

it('tests HatenaLiDOMElement performs correctly.', function () {
    $basicHatenaLiDOMElement = new HatenaLiDOMElement(
        table: [
            'header' => 'header test',
            'lines' => [
                'line 1',
                'line 2',
                'line 3',
            ]
        ]
    );

    expect($basicHatenaLiDOMElement->__toString())
        ->toBe(
            <<<CONTENT
-header test
--line 1
--line 2
--line 3

CONTENT
        )
        ->and($basicHatenaLiDOMElement->__toString())
        ->toBe($basicHatenaLiDOMElement->__toStringWithFootNote());
});

it('tests foot note being attached correctly.', function () {
    $hatenaLiDOMElementWithFootNotes = new HatenaLiDOMElement(
        table: [
            'header' => 'header test',
            'lines' => [
                'line 1 hoge foo',
                'line 2',
                'line 3 fuga bar',
            ]
        ],
        tag: '+',
        footNotes: [
            new FootNote('hoge', 'hoge脚注'),
            new FootNote('fuga', 'fuga脚注'),
        ]
    );
    expect($hatenaLiDOMElementWithFootNotes->__toStringWithFootNote())
        ->toBe(
            <<<CONTENT
+header test
++line 1 hoge(( hoge脚注 )) foo
++line 2
++line 3 fuga(( fuga脚注 )) bar

CONTENT
        );
});

it('tests throw exception correctly.', function () {
    expect(fn () => new HatenaLiDOMElement(
        table: [
            'header' => 'header test',
            'lines' => [
                'line 1',
                'line 2',
                'line 3',
            ]
        ],
        tag: '',
        footNotes: [new stdClass()]
    ))->toThrow(HatenaInvalidArgumentException::class);
});