<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses;

interface HatenaEditPostByEntryIdResponseInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getParsedData(): array;
}