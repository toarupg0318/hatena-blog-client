<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses;

interface HatenaPostResponseInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getParsedData(): array;
}