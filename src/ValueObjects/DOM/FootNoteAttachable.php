<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Toarupg0318\HatenaBlogClient\ValueObjects\FootNote;

/**
 * Class which implements this interface can be attached "foot note" syntax.
 *
 * @internal
 */
interface FootNoteAttachable
{
    /**
     * @return self
     */
    public function attachFootNote(FootNote $footNote): self;

    /**
     * Transform input to foot note attached value.
     *
     * @return string
     */
    public function __toStringWithFootNote(): string;
}