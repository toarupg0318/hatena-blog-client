<?php

use Toarupg0318\HatenaBlogClient\Concerns\ExtractPageValueFromLinkTrait;

$classUsingExtractPageValueFromLinkTrait = new class {
    use ExtractPageValueFromLinkTrait;

    public function handle(string $linkUrl): string
    {
        return $this->extractPageValueFromLink($linkUrl);
    }
};

$pageValue = '1780929531';
$testData = [
    'https://blog.hatena.ne.jp/hoge0318/fuga0318.hatenablog.com/atom/entry?page=' . $pageValue,
    $pageValue,
];
it(
    description: 'tests ExtractPageValueFromLinkTrait performs correctly.',
    closure: function (string $pageUrlOrQueryParam) use ($classUsingExtractPageValueFromLinkTrait, $pageValue) {
        expect($classUsingExtractPageValueFromLinkTrait->handle($pageUrlOrQueryParam))
            ->toBe($pageValue)
        ;
    }
)
    ->with($testData);