<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaBrDOMElement;

it('tests HatenaBrDOMElement is instantiated correctly.', function () {
    $hatenaBrDOMElement = new HatenaBrDOMElement();
    expect(strval($hatenaBrDOMElement))
        ->toBe(PHP_EOL . PHP_EOL);
});
