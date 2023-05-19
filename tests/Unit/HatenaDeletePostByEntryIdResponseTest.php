<?php

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\HatenaDeletePostByEntryIdResponse;

$dummyResponseBody = '';

$guzzleResponseMock = Mockery::mock(Response::class);
$guzzleStreamMock = Mockery::mock(Stream::class);
$guzzleResponseMock
    ->shouldReceive('getStatusCode')
    ->andReturns(200);
$guzzleResponseMock
    ->shouldReceive('getHeaders')
    ->andReturns([
        "Server" => ["nginx"],
        "Date" => ["Wed, 10 May 2023 04:35:25 GMT"],
        "Content-Type" => ["application/atom+xml; charset=utf-8; type=feed"],
        "Transfer-Encoding" => ["chunked"],
        "Connection" => ["keep-alive"],
        "Vary" => ["Accept-Encoding", "Accept-Language,Cookie,Accept-Encoding"],
        "Set-Cookie" => [
            "b=$1$24nHWGvr$0FjIBiO0WEfPZBJ7RzKFx1; expires=Tue, 05 May 2043 04:35:25 GMT; domain=example.com; path=/",
            "ek=; path=/; expires=Wed, 10-May-2023 03:35:25 GMT",
            "sk=ebde5674e82b84bed2e17ac870ed94fda1efc4e8; path=/"
        ],
        "Cache-Control" => ["private"],
        "Content-Security-Policy-Report-Only" => ["block-all-mixed-content; report-uri https://example.com/api/csp_report"],
        "P3P" => ["CP=\"OTI CUR OUR BUS STA\""],
        "X-Content-Type-Options" => ["nosniff"],
        "X-Dispatch" => ["Example::Web::Admin::User::Blog::AtomPub#list"],
        "X-Frame-Options" => ["DENY"],
        "X-Revision" => ["ef047b236870653fbe30bff847ec33"],
        "X-XSS-Protection" => ["1"],
        "X-Runtime" => ["0.054778"],
        "X-Proxy-Revision" => ["f843f7e"]
    ]);
$guzzleResponseMock
    ->shouldReceive('getBody')
    ->andReturns($guzzleStreamMock);
$guzzleStreamMock
    ->shouldReceive('getContents')
    ->andReturns($dummyResponseBody);
$guzzleResponseMock
    ->shouldReceive('getProtocolVersion')
    ->andReturns('1.1');
$guzzleResponseMock
    ->shouldReceive('getReasonPhrase')
    ->andReturns('OK');

if (! $guzzleResponseMock instanceof ResponseInterface) {
    throw new LogicException();
}
$deleteResponseMock = new HatenaDeletePostByEntryIdResponse($guzzleResponseMock);

it(
    'tests getParsedData() performs correctly.',
    function () use ($deleteResponseMock, $dummyResponseBody) {
        $deleteResponseReflection = new ReflectionClass($deleteResponseMock);
        $deleteByEntryIdResponseMethod = $deleteResponseReflection
            ->getMethod('getParsedData');
        $deleteByEntryIdResponseMethod->setAccessible(true);
        $deleteResponseContents = $deleteByEntryIdResponseMethod
            ->invoke($deleteResponseMock);
        expect($deleteResponseContents)
            ->toBe([]);
    }
);