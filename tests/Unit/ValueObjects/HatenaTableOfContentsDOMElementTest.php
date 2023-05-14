<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTableOfContentsDOMElement;

it('tests HatenaTableOfContentsDOMElement performs correctly.', function () {
    $hatenaTableOfContentsDOMElement = new HatenaTableOfContentsDOMElement();

    expect($hatenaTableOfContentsDOMElement->__toString())
        ->toBe("[:contents]\n");
});
