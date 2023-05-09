<?php

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTwitterDOMElement;

it('tests HatenaTwitterDOMElement performs correctly.', function () {
    // https://twitter.com/toarupg0318/status/1640997727260794882
    $testTwitterStatus = '1640997727260794882';
    expect(strval(new HatenaTwitterDOMElement($testTwitterStatus)))
        ->toBe('twitter:1640997727260794882:tweet');
});

it('tests createFromHttpsValue() performs correctly.', function () {
    $testTwitterURL = 'https://twitter.com/toarupg0318/status/1640997727260794882';
    expect(strval(HatenaTwitterDOMElement::createFromHttpsValue($testTwitterURL)))
        ->toBe('twitter:1640997727260794882:tweet');
});

it('tests if passed param is invalid, createFromHttpsValue() throws Exception correctly.', function () {
    $testInvalidTwitterURL = 'https://twittttttttter.com/toarupg0318/status/1640997727260794882';
    expect(fn () => HatenaTwitterDOMElement::createFromHttpsValue($testInvalidTwitterURL))
        ->toThrow(HatenaUnexpectedException::class, 'Twitter URL is invalid.');

    $testEmptyValue = '';
    expect(fn () => HatenaTwitterDOMElement::createFromHttpsValue($testEmptyValue))
        ->toThrow(HatenaUnexpectedException::class, 'Twitter URL is invalid.');
});