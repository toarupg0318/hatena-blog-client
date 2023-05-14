<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaReadMoreElement;

it('tests HatenaReadMoreElement performs correctly.', function () {
    $hatenaReadMoreElement = new HatenaReadMoreElement();

    expect($hatenaReadMoreElement->__toString())
        ->toBe("====\n");
});