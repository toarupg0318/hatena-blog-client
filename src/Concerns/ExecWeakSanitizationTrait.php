<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns;

trait ExecWeakSanitizationTrait
{
    /**
     * @note activate "const in trait" on PHP8.2+
     *
     * @var array|string[]
     */
    private array $affectElements = [
        'script_tag' => '&lt;script&gt;',
    ];

    public function execWeakSanitizationTrait(string $input): string
    {
        if (str_contains($input, $this->affectElements['script_tag'])) {
            return str_replace(
                search: $this->affectElements['script_tag'],
                replace: $this->affectElements['script_tag'] . 'return;',
                subject: $input
            );
        }

        return $input;
    }
}