<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\HatenaDOMDocument;

it('tests append** methods perform correctly.', function () {
    $hatenaDOMDocument = HatenaDOMDocument::create();
    $domDocumentAllDomAppended = $hatenaDOMDocument
        ->appendH3('見出しh3')
        ->appendH4('見出しh4')
        ->appendH5('見出しh5')
        ->appendBlockQuote('block quote test', 'https://github.com/toarupg0318/hatena-blog-client')
        ->appendBr()
        ->appendDt('タイトルtest', '説明test')
        ->appendHttp('https://github.com/toarupg0318/hatena-blog-client', 'barcode')
        ->appendId('toarupg0318')
        ->appendLi([
            'header' => 'ヘッダーtest',
            'lines' => ['行1test', '行2test', '行3test']
        ], '+')
        ->appendPre('pre')
        ->appendSuperPre('super pre')
        ->appendReadMore()
        ->appendStopP('pタグ停止記法test')
        ->appendSyntaxHighLight('typescript', <<<TYPESCRIPT
const message: string = "Hello, TypeScript!";
console.log(message);
TYPESCRIPT
)
        ->appendTable([
            'headers' => ['(1,1) test', '(2,1)test'],
            'lines' => [
                ['(1,2) test', '(2,2)test'],
                ['(1,3) test', '(2,3)test'],
            ]
        ])
        ->appendTableOfContents()
        ->appendTex('\[\sin x = \sum_{n=0}^{\infty} \frac{(-1)^n}{(2n+1)!} x^{2n+1}\]')
        ->appendText('テキストtest')
        ->appendTwitter('1657370105372889088')
    ;

    expect(count($domDocumentAllDomAppended))
        ->toBe(19)
        ->and($domDocumentAllDomAppended->__toString())
        ->toBe(
            <<<HATENA
*見出しh3

**見出しh4

***見出しh5

>https://github.com/toarupg0318/hatena-blog-client//>
block quote test
<<



:タイトルtest:説明test
[https://github.com/toarupg0318/hatena-blog-client:barcode]

id:toarupg0318

+ヘッダーtest
++行1test
++行2test
++行3test

>|
pre
|<
>||
super pre
||<
====

><
pタグ停止記法test
><
>|typescript|
const message: string = "Hello, TypeScript!";
console.log(message);
||<
|*(1,1) test|*(2,1)test|
|(1,2) test|(2,2)test|
|(1,3) test|(2,3)test|

[:contents]

[tex:\[\sin x = \sum_{n=0}^{\infty} \\frac{(-1)^n}{(2n+1)!} x^{2n+1}\]]

テキストtest

twitter:1657370105372889088:tweet

HATENA
        );

});