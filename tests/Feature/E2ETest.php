<?php

use Dotenv\Dotenv;
use Toarupg0318\HatenaBlogClient\HatenaClient;

// init env
$env = Dotenv::createUnsafeImmutable(
    __DIR__ . '/../..',
    ['.env', '.env.example']
);
$env->safeLoad();

$hatenaId = getenv('HATENA_ID');
$hatenaApiKey = getenv('HATENA_API_KEY');
dump('test hoge');
dump(mb_strlen($hatenaId));
dump(mb_strlen($hatenaApiKey));
dump(mb_strlen($_ENV['HATENA_ID']));
dump(mb_strlen($_ENV['HATENA_API_KEY']));
//
//$hatenaClient = HatenaClient::getInstance($hatenaId, $hatenaApiKey);
//
//it('ensures straight through test connects to Hatena with using actual account', function () {
//    // post a new blog entry, called as "registered-entry" below.
//    sleep(1);
//
//    // get list, confirm registered-entry is contained.
//    sleep(1);
//
//    // get the registered-entry, confirm registered-entry is fetched correctly.
//    sleep(1);
//
//    // edit the registered-entry, confirm registered-entry is edited correctly.
//    sleep(1);
//
//    // delete the registered-entry
//    sleep(1);
//
//    // get the registered-entry, confirm registered-entry is not found.
//    sleep(1);
//});