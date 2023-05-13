<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\HatenaDOMDocument;

it('tests appendH3() performs correctly.', function () {
    $hatenaDOMDocument = HatenaDOMDocument::create();
    $h3Value = 'test h3';
    $domDocumentH3Appended = $hatenaDOMDocument->appendH3($h3Value);

    expect(count($domDocumentH3Appended))
        ->toBe(1)
        ->and($domDocumentH3Appended->__toString())
        ->toBe("*test h3\n");
});