<?php

// init env
use Toarupg0318\HatenaBlogClient\HatenaClient;

$env = \Dotenv\Dotenv::createUnsafeImmutable(
    __DIR__ . '/../..',
    '.env'
);
$env->safeLoad();

$hatenaId = getenv('HATENA_ID');
$hatenaApiKey = getenv('HATENA_API_KEY');

// todo: ブログエントリの一覧取得
//// todo: getParsedData
//// todo: getFirstPageUrl
//// todo: getNextPageUrl
//if (
//    !is_string($hatenaId) || !is_string($hatenaApiKey) ||
//    empty($hatenaId) || empty($hatenaApiKey)
//) {
//    throw new Exception('Secrets required for test is not loaded correctly.');
//}
//
//$hatenaClient = HatenaClient::getInstance($hatenaId, $hatenaApiKey);
//
//it('tests get list feature.', function () use ($hatenaClient) {
//    $hatenaClient->getList();
//});

// todo: ブログエントリの取得
// todo: ブログエントリの投稿
// todo: ブログエントリの編集
// todo: ブログエントリの削除