<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses;

interface HatenaGetListResponseInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getParsedData(): array;

    /**
     * @return string|null
     */
    public function getFirstPageUrl(): string|null;

    /**
     * @return string|null
     */
    public function getNextPageUrl(): string|null;
}