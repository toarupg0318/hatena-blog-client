<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaHTTPDOMElement;

it('tests HatenaHTTPDOMElement performs correctly.', function () {
    $testUrl = 'https://github.com/toarupg0318/hatena-blog-client';
    $hatenaHTTPDOMElementWithNoTag = new HatenaHTTPDOMElement($testUrl);
    expect($hatenaHTTPDOMElementWithNoTag->__toString())
        ->toBe("[https://github.com/toarupg0318/hatena-blog-client:]\n");

    foreach (HatenaHTTPDOMElement::OPTIONAL_TAGS as $optionalTag) {
        $hatenaHTTPDOMElement = new HatenaHTTPDOMElement($testUrl, $optionalTag);
        expect($hatenaHTTPDOMElement->__toString())
            ->toBe("[https://github.com/toarupg0318/hatena-blog-client:{$optionalTag}]\n");
    }
});
