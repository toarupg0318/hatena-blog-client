<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaH5DOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

it('tests HatenaH5DOMElement performs correctly', function () {
    $hatenaH5DOMElementDoesntHaveFootNote = new HatenaH5DOMElement('h5 見出し テスト hoge fuga piyo hoge');
    expect(strval($hatenaH5DOMElementDoesntHaveFootNote))
        ->toBe("***h5 見出し テスト hoge fuga piyo hoge\n")
        ->and($hatenaH5DOMElementDoesntHaveFootNote->__toStringWithFootNote())
        ->toBe("***h5 見出し テスト hoge fuga piyo hoge\n");

    $hatenaH5DOMElementHaveFootNote = (new HatenaH5DOMElement('h5 見出し テスト はてなブログ hoge fuga はてなブログ piyo hoge'))
        ->attachFootNote(
            new FootNote(
                vocabulary: 'はてなブログ',
                description: 'はてなブログは、あなたの「書きたい」気持ちに応えるブログサービスです。'
            )
        );
    expect(strval($hatenaH5DOMElementHaveFootNote))
        ->toBe("***h5 見出し テスト はてなブログ hoge fuga はてなブログ piyo hoge\n")
        ->and($hatenaH5DOMElementHaveFootNote->__toStringWithFootNote())
        ->toBe("***h5 見出し テスト はてなブログ(( はてなブログは、あなたの「書きたい」気持ちに応えるブログサービスです。 )) hoge fuga はてなブログ piyo hoge\n");

    $hatenaH5DOMElementHaveMultipleFootNotes = (new HatenaH5DOMElement('h5 見出し テスト 複数脚注 hoge fuga piyo '))
        ->attachFootNote(new FootNote('hoge', 'hoge脚注'))
        ->attachFootNote(new FootNote('fuga', 'fuga脚注'))
        ->attachFootNote(new FootNote('piyo', 'piyo脚注'));
    expect($hatenaH5DOMElementHaveMultipleFootNotes->__toStringWithFootNote())
        ->toBe("***h5 見出し テスト 複数脚注 hoge(( hoge脚注 )) fuga(( fuga脚注 )) piyo(( piyo脚注 )) \n");
});