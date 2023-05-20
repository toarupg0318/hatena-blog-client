<?php

use Toarupg0318\HatenaBlogClient\HatenaClient;

$hatenaId = getenv('HATENA_ID');
$hatenaBlogId = getenv('HATENA_BLOG_ID');
$hatenaApiKey = getenv('HATENA_API_KEY');
$auth = 'wsse';

$hatenaClient = HatenaClient::getInstance($hatenaId, $hatenaBlogId, $hatenaApiKey, $auth);

it('tests request with wsse header performs correctly.', function () use ($hatenaClient) {
    $result = false;
    try {
        sleep(1);
        $getListResponse = $hatenaClient->getList();

        $nextPageUrl = $getListResponse->getNextPageUrl();
        if ($nextPageUrl !== null) {
            sleep(1);
            $hatenaClient->getList($nextPageUrl);
        }
        $result = true;
    } catch (Exception $exception) {
        $result = false;
    }

    expect($result)
        ->toBeTrue();
});