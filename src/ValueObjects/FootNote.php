<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects;

final class FootNote
{
    public readonly string $vocabulary;

    public function __construct(
        string $vocabulary,
        public readonly string $description
    ) {
        $this->vocabulary = trim($vocabulary);
    }
}