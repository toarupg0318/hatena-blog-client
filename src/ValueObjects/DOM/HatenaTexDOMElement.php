<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

final class HatenaTexDOMElement extends HatenaDOMElement
{
    public function __construct(
        private readonly string $texScript
    ) {
    }

    /**
     * @return string
     *
     * @example
     * ```
     *  $this->texScript:
     *      'D = P^{-1} A P'
     *  return:
     *      '[tex:D = P^{-1} A P]'
     *  indicates:
     *      D=P−1AP
     * ```
     * @example
     * ```
     *  $this->texScript:
     *      '\[\sin x = \sum_{n=0}^{\infty} \frac{(-1)^n}{(2n+1)!} x^{2n+1}\]'
     *  return:
     *      '[tex:\[\sin x = \sum_{n=0}^{\infty} \frac{(-1)^n}{(2n+1)!} x^{2n+1}\]]'
     *  indicates:
     *      [sinx=∑∞n=0(−1)n(2n+1)!x2n+1]
     * ```
     */
    public function __toString(): string
    {
        return '[tex:' . $this->texScript . ']' . PHP_EOL;
    }
}