<?php

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaSyntaxHighLightDOMElement;

it('tests HatenaSyntaxHighLightDOMElement performs correctly.', function () {
    $hatenaSyntaxHighLightDOMElement = new HatenaSyntaxHighLightDOMElement(
        'php',
        'php script'
    );
    expect($hatenaSyntaxHighLightDOMElement->__toString())
        ->toBe(
            <<<HATENA
>|php|
php script
||<
HATENA
        );
});

it('tests throw exception correctly.', function () {
    expect(fn () => new HatenaSyntaxHighLightDOMElement(
        'hoge言語',
        'fuga script'
    ))
        ->toThrow(HatenaInvalidArgumentException::class);
});