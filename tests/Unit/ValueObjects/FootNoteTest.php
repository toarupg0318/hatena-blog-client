<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

it('tests FootNote is instantiated correctly.', function () {
    $vocabulary = 'hoge vocabulary';
    $description = 'fuga description';
    $footNote = new FootNote($vocabulary, $description);
    expect([$footNote->vocabulary, $footNote->description])
        ->toMatchArray([$vocabulary, $description]);
});

it('tests passed vocabulary is trimmed correctly.', function () {
    $vocabulary = ' hoge vocabulary to trim ';
    $description = ' fuga description ';
    $footNote = new FootNote($vocabulary, $description);
    expect([$footNote->vocabulary, $footNote->description])
        ->toMatchArray(['hoge vocabulary to trim', $description]);
});