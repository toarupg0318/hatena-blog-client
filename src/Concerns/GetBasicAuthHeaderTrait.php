<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns;

trait GetBasicAuthHeaderTrait
{
    /**
     * @return array{headers: array<string, string>}
     */
    private function getBasicAuthHeader(string $hatenaId, string $apiKey): array
    {
        return [
            'headers' => [
                'Content-Type' => 'application/atomsvc+xml; charset=utf-8',
                'Authorization' => 'Basic ' . base64_encode($hatenaId . ':' . $apiKey),
            ],
        ];
    }
}