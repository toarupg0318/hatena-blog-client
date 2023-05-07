<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaTextDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

it('tests HatenaTextDOMElement performs correctly', function () {
    $hatenaTextDOMElementDoesntHaveFootNote = new HatenaTextDOMElement('basic test 見出し テスト hoge fuga piyo hoge');
    expect(strval($hatenaTextDOMElementDoesntHaveFootNote))
        ->toBe("basic test 見出し テスト hoge fuga piyo hoge\n")
        ->and($hatenaTextDOMElementDoesntHaveFootNote->__toStringWithFootNote())
        ->toBe("basic test 見出し テスト hoge fuga piyo hoge\n");

    $hatenaTextDOMElementHaveFootNote = (new HatenaTextDOMElement('basic test 見出し テスト はてなブログ hoge fuga はてなブログ piyo hoge'))
        ->attachFootNote(
            new FootNote(
                vocabulary: 'はてなブログ',
                description: 'はてなブログは、あなたの「書きたい」気持ちに応えるブログサービスです。'
            )
        );
    expect(strval($hatenaTextDOMElementHaveFootNote))
        ->toBe("basic test 見出し テスト はてなブログ hoge fuga はてなブログ piyo hoge\n")
        ->and($hatenaTextDOMElementHaveFootNote->__toStringWithFootNote())
        ->toBe("basic test 見出し テスト はてなブログ(( はてなブログは、あなたの「書きたい」気持ちに応えるブログサービスです。 )) hoge fuga はてなブログ piyo hoge\n");

    $hatenaTextDOMElementHaveMultipleFootNotes = (new HatenaTextDOMElement('basic test 見出し テスト 複数脚注 hoge fuga piyo '))
        ->attachFootNote(new FootNote('hoge', 'hoge脚注'))
        ->attachFootNote(new FootNote('fuga', 'fuga脚注'))
        ->attachFootNote(new FootNote('piyo', 'piyo脚注'));
    expect($hatenaTextDOMElementHaveMultipleFootNotes->__toStringWithFootNote())
        ->toBe("basic test 見出し テスト 複数脚注 hoge(( hoge脚注 )) fuga(( fuga脚注 )) piyo(( piyo脚注 )) \n");
});