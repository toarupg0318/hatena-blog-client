<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\HatenaClient;

trait GetBasicAuthHeaderTrait
{
    /**
     * todo: no guzzle, test
     * @param string $hatenaId
     * @param string $apiKey
     * @return array
     */
    public function getBasicAuthHeader(string $hatenaId, string $apiKey): array
    {
        return [
            'headers' => [
                'Content-Type' => 'application/atomsvc+xml; charset=utf-8',
            ],
            'auth' => [$hatenaId, $apiKey]
        ];
    }
}