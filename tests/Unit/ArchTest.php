<?php

test('Concerns must have no dependencies')
    ->expect('Toarupg0318\HatenaBlogClient\Concerns')
    ->toUseNothing();

test('ResponseContracts must have no dependencies')
    ->expect('Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses')
    ->toUseNothing();

test('Exceptions must have no dependencies')
    ->expect('Toarupg0318\HatenaBlogClient\Exceptions')
    ->toUseNothing();

test('ValueObjects must have no dependencies')
    ->expect('Toarupg0318\HatenaBlogClient\ValueObjects')
    ->toUseNothing();