<?php

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTableDOMElement;

it('tests HatenaTableDOMElement performs correctly.', function () {
    $basicHatenaTableDOMElement = new HatenaTableDOMElement(
        table: [
            'headers' => [
                'h(1,1)', 'h(2,1)', 'h(3,2)'
            ],
            'lines' => [
                ['l(1,2)', 'l(2,2)', 'l(3,2)'],
                ['l(1,3)', 'l(2,3)', 'l(3,3)'],
                ['l(1,4)', 'l(2,4)', 'l(3,4)'],
            ]
        ]
    );
    expect($basicHatenaTableDOMElement->__toString())
        ->toBe(
            <<<CONTENT
|*h(1,1)|*h(2,1)|*h(3,2)|
|l(1,2)|l(2,2)|l(3,2)|
|l(1,3)|l(2,3)|l(3,3)|
|l(1,4)|l(2,4)|l(3,4)|

CONTENT
        );
});

it('tests The number of columns in the header does not equal the number of columns in each row.', function () {
    expect(fn () => new HatenaTableDOMElement(
        table: [
            'headers' => [
                'h(1,1)', 'h(2,1)', 'h(3,2)'
            ],
            'lines' => [
                ['l(1,2)', 'l(2,2)', 'l(3,2)'],
                ['l(1,3)', 'l(2,3)', 'l(3,3)', 'l(4,3)'],
                ['l(1,4)', 'l(2,4)', 'l(3,4)'],
            ]
        ]
    ))->toThrow(HatenaInvalidArgumentException::class);
});