<?php

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaHttpException;
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
    sleep(1);

    // get the registered-entry, confirm registered-entry is fetched correctly.
    $hatenaClient->getPostByEntryId($registeredEntryId)
        ->getParsedData();
    sleep(1);

    // get list, confirm registered-entry is contained.
    $getListResponseObject = $hatenaClient->getList();
    $getListResponseObject->getParsedData();
    sleep(1);

//    // edit the registered-entry, confirm registered-entry is edited correctly.
    $contentToEdit = HatenaDOMDocument::create()
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
        ->appendTwitter('1657370105372889088');
    $editResponse = $hatenaClient
        ->edit(
            entryId: $registeredEntryId,
            content: $contentToEdit,
            draft: false,
            customUrl: 'im toarupg'
        )
        ->getParsedData();
    expect($editResponse)
        ->toHaveKeys([
            'id',
            'link',
            'author',
            'title',
            'updated',
            'published',
            'summary',
            'content',
//            'category',
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
        );
    sleep(1);

    // delete the registered-entry
    $hatenaClient->deletePostByEntryId($registeredEntryId);
    sleep(1);

    // get the registered-entry, confirm registered-entry is not found.
    expect(fn () => $hatenaClient->getPostByEntryId($registeredEntryId))
        ->toThrow(HatenaHttpException::class);
});