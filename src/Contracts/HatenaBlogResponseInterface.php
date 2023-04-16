<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Contracts;

interface HatenaBlogResponseInterface
{
    /**
     * @return string
     */
    public function getParsedData(): string;
}