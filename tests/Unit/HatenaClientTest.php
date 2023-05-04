<?php

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;
use Toarupg0318\HatenaBlogClient\HatenaClient;

$hatenaClient = HatenaClient::getInstance('hoge0123', 'fuga0987');

it('tests getPostByEntryId() throws exception if passed entryId is empty.', function () use ($hatenaClient) {
    expect(fn () => $hatenaClient->getPostByEntryId(''))
        ->toThrow(
            exception: HatenaInvalidArgumentException::class,
            exceptionMessage: 'Entry id is empty.'
        );
});

it('tests deletePostByEntryId() throws exception if passed entryId is empty.', function () use ($hatenaClient) {
    expect(fn () => $hatenaClient->deletePostByEntryId(''))
        ->toThrow(
            exception: HatenaInvalidArgumentException::class,
            exceptionMessage: 'Entry id is empty.'
        );
});

it('tests edit() throws exception if passed entryId is empty.', function () use ($hatenaClient) {
    expect(fn () => $hatenaClient->deletePostByEntryId(''))
        ->toThrow(
            exception: HatenaInvalidArgumentException::class,
            exceptionMessage: 'Entry id is empty.'
        );
});