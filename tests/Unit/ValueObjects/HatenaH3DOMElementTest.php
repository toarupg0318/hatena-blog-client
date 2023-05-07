<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaH3DOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

it('tests HatenaH3DOMElement performs correctly', function () {
    $hatenaH3DOMElementDoesntHaveFootNote = new HatenaH3DOMElement('h3 見出し テスト hoge fuga piyo hoge');
    expect(strval($hatenaH3DOMElementDoesntHaveFootNote))
        ->toBe("*h3 見出し テスト hoge fuga piyo hoge\n")
        ->and($hatenaH3DOMElementDoesntHaveFootNote->__toStringWithFootNote())
        ->toBe("*h3 見出し テスト hoge fuga piyo hoge\n");

    $hatenaH3DOMElementHaveFootNote = (new HatenaH3DOMElement('h3 見出し テスト はてなブログ hoge fuga はてなブログ piyo hoge'))
        ->attachFootNote(
            new FootNote(
                vocabulary: 'はてなブログ',
                description: 'はてなブログは、あなたの「書きたい」気持ちに応えるブログサービスです。'
            )
        );
    expect(strval($hatenaH3DOMElementHaveFootNote))
        ->toBe("*h3 見出し テスト はてなブログ hoge fuga はてなブログ piyo hoge\n")
        ->and($hatenaH3DOMElementHaveFootNote->__toStringWithFootNote())
        ->toBe("*h3 見出し テスト はてなブログ(( はてなブログは、あなたの「書きたい」気持ちに応えるブログサービスです。 )) hoge fuga はてなブログ piyo hoge\n");

    $hatenaH3DOMElementHaveMultipleFootNotes = (new HatenaH3DOMElement('h3 見出し テスト 複数脚注 hoge fuga piyo '))
        ->attachFootNote(new FootNote('hoge', 'hoge脚注'))
        ->attachFootNote(new FootNote('fuga', 'fuga脚注'))
        ->attachFootNote(new FootNote('piyo', 'piyo脚注'));
    expect($hatenaH3DOMElementHaveMultipleFootNotes->__toStringWithFootNote())
        ->toBe("*h3 見出し テスト 複数脚注 hoge(( hoge脚注 )) fuga(( fuga脚注 )) piyo(( piyo脚注 )) \n");
});