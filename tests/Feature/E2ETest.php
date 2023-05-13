<?php

use Toarupg0318\HatenaBlogClient\HatenaClient;
use Toarupg0318\HatenaBlogClient\ValueObjects\HatenaDOMDocument;

$hatenaId = getenv('HATENA_ID');
$hatenaBlogId = getenv('HATENA_BLOG_ID');
$hatenaApiKey = getenv('HATENA_API_KEY');

$hatenaClient = HatenaClient::getInstance($hatenaId, $hatenaBlogId, $hatenaApiKey);

it('ensures straight through test connects to Hatena with using actual account', function () use ($hatenaClient) {
    // post a new blog entry, called as "registered-entry" below.
    $content = HatenaDOMDocument::create()
        ->appendH3('見出しh3')
        ->appendH4('見出しh4');
    $title = 'タイトルテスト'
        . (new DateTime('now', new DateTimeZone('Asia/Tokyo')))
            ->format('Y_m_d_H_i_s');
    $categories = ['hoge category', 'fuga category'];
    $postResponseObject = $hatenaClient
        ->post(
            $content,
            $title,
            'text/plain',
            true,
            null,
            null,
            $categories
        );
    $postResponse = $postResponseObject->getParsedData();
    expect($postResponse)
        ->toHaveKeys([
            'id',
            'link',
            'author',
            'title',
            'updated',
            'published',
            'summary',
            'content',
            'category',
        ])
        ->and($postResponse['author'] ?? [])
        ->toHaveKey('name')
        ->and($postResponse['title'] ?? null)
        ->toBe($title)
        ->and($postResponse['content'] ?? null)
        ->toBe(expected: PHP_EOL
            . $content->__toString()
            . PHP_EOL
            . '  '  // additional blanks
        )
        ->and($postResponse['category'] ?? [])
        ->toBeArray();

    $fetchedCategories = $postResponse['category'];
    if (! is_array($fetchedCategories)) {
        throw new LogicException();
    }
    foreach ($fetchedCategories as $fetchedCategory) {
        expect($fetchedCategory['@attributes']['term'] ?? null)
            ->toBeIn($categories);
    }
    $registeredEntryId = $postResponseObject->getEntryId();
    dump($registeredEntryId);
    sleep(1);

    // get the registered-entry, confirm registered-entry is fetched correctly.
    $hatenaClient->getPostByEntryId($registeredEntryId);
    sleep(1);

    // get list, confirm registered-entry is contained.
    $getListResponseObject = $hatenaClient->getList();
    $getListResponse = $getListResponseObject->getParsedData();
    sleep(1);
//
//    // edit the registered-entry, confirm registered-entry is edited correctly.
//    sleep(1);
//
//    // delete the registered-entry
//    sleep(1);
//
//    // get the registered-entry, confirm registered-entry is not found.
//    sleep(1);
});