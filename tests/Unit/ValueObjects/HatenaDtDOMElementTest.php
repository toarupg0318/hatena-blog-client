<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDtDOMElement;

it('tests HatenaDtDOMElement performs correctly.', function () {
    $hatenaDtDOMElement = new HatenaDtDOMElement('test title', 'test description');
    expect(strval($hatenaDtDOMElement))
        ->toBe(':test title:test description');
});
