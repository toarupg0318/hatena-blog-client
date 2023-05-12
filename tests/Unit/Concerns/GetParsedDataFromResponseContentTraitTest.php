<?php

use Toarupg0318\HatenaBlogClient\Concerns\GetParsedDataFromResponseContentTrait;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;

$classUsingGetParsedDataFromResponseContentTrait = new class () {
    use GetParsedDataFromResponseContentTrait;

    /**
     * @param string $content
     * @return array<string, mixed>
     */
    public function output(string $content): array
    {
        return $this->getParsedDataFromResponseContent($content);
    }
};

it('getParsedDataFromResponseContent performs correctly.', function () use ($classUsingGetParsedDataFromResponseContentTrait) {
    $dummyXml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom"
       xmlns:app="http://www.w3.org/2007/app">
  <title>新しいタイトル</title>
  <author><name>name</name></author>
  <content type="text/plain">
    ** 新しい本文
  </content>
  <updated>2008-01-01T00:00:00</updated>
  <category term="Scala" />
  <app:control>
    <app:draft>no</app:draft>
  </app:control>
  <hatenablog:custom-url xmlns:hatenablog="http://www.hatena.ne.jp/info/xmlns#hatenablog">2009-happy-new-year</hatenablog:custom-url>
</entry>
XML;
    expect($classUsingGetParsedDataFromResponseContentTrait->output($dummyXml))
        ->toMatchArray([
            'title' => '新しいタイトル',
            'author' => [
                'name' => 'name'
            ],
            'content' => "\n    ** 新しい本文\n  ",
            'updated' => '2008-01-01T00:00:00',
            'category' => [
                '@attributes' => [
                    'term' => 'Scala'
                ]
            ]
        ]);
});

it('getParsedDataFromResponseContent throws exception correctly.', function () use ($classUsingGetParsedDataFromResponseContentTrait) {
    $dummyInvalidXml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom"
       xmlns:app="http://www.w3.org/2007/app">
  <title>新しいタイトル</title>
  <author><name>name</name></author>
  <content type="text/plain">
    ** 新しい本文
  </content>
  <updated>2008-01-01T00:00:00</updated>
  <category term="Scala" />
  <app:control>
    <app:draft>no</app:draft>
  </app:control>
  <hatenablog:custom-url xmlns:hatenablog="http://www.hatena.ne.jp/info/xmlns#hatenablog">2009-happy-new-year</hatenablog:custom-url>
</entry______________________________>
XML;
    expect(fn () => $classUsingGetParsedDataFromResponseContentTrait->output($dummyInvalidXml))
        ->toThrow(
            HatenaUnexpectedException::class,
            'Failed to process the response from Hatena.'
        );
});