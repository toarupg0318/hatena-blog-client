<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTexDOMElement;

it('tests HatenaTexDOMElement performs correctly.', function () {
    $hatenaTexDOMElement = new HatenaTexDOMElement('hoge text');

    expect($hatenaTexDOMElement->__toString())
        ->toBe("[tex:hoge text]\n");
});
