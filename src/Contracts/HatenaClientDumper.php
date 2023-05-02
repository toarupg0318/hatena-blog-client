<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Contracts;

/**
 * @template T
 */
interface HatenaClientDumper
{
    /**
     * @return T
     */
    public function dump(): mixed;

    /**
     * @return never
     */
    public function dd(): never;
}