<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaBlockQuoteDOMElement;

it('tests HatenaBlockQuoteDOMElement performs correctly.', function () {
    $hatenaBlockQuoteDOMElement = new HatenaBlockQuoteDOMElement('test value', 'test url');

    expect($hatenaBlockQuoteDOMElement->__toString())
        ->toBe(
            <<<HATENA
&gt;test url//&gt;
test value
&lt;&lt;
HATENA
        );
});
