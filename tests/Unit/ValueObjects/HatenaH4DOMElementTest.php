<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaH4DOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

it('tests HatenaH4DOMElement performs correctly', function () {
    $hatenaH4DOMElementDoesntHaveFootNote = new HatenaH4DOMElement('h4 見出し テスト hoge fuga piyo hoge');
    expect(strval($hatenaH4DOMElementDoesntHaveFootNote))
        ->toBe("**h4 見出し テスト hoge fuga piyo hoge\n")
        ->and($hatenaH4DOMElementDoesntHaveFootNote->__toStringWithFootNote())
        ->toBe("**h4 見出し テスト hoge fuga piyo hoge\n");

    $hatenaH4DOMElementHaveFootNote = (new HatenaH4DOMElement('h4 見出し テスト はてなブログ hoge fuga はてなブログ piyo hoge'))
        ->attachFootNote(
            new FootNote(
                vocabulary: 'はてなブログ',
                description: 'はてなブログは、あなたの「書きたい」気持ちに応えるブログサービスです。'
            )
        );
    expect(strval($hatenaH4DOMElementHaveFootNote))
        ->toBe("**h4 見出し テスト はてなブログ hoge fuga はてなブログ piyo hoge\n")
        ->and($hatenaH4DOMElementHaveFootNote->__toStringWithFootNote())
        ->toBe("**h4 見出し テスト はてなブログ(( はてなブログは、あなたの「書きたい」気持ちに応えるブログサービスです。 )) hoge fuga はてなブログ piyo hoge\n");

    $hatenaH4DOMElementHaveMultipleFootNotes = (new HatenaH4DOMElement('h4 見出し テスト 複数脚注 hoge fuga piyo '))
        ->attachFootNote(new FootNote('hoge', 'hoge脚注'))
        ->attachFootNote(new FootNote('fuga', 'fuga脚注'))
        ->attachFootNote(new FootNote('piyo', 'piyo脚注'));
    expect($hatenaH4DOMElementHaveMultipleFootNotes->__toStringWithFootNote())
        ->toBe("**h4 見出し テスト 複数脚注 hoge(( hoge脚注 )) fuga(( fuga脚注 )) piyo(( piyo脚注 )) \n");
});