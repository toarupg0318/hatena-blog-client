<?php

use Toarupg0318\HatenaBlogClient\Concerns\GetBasicAuthHeaderTrait;

$classUsingGetBasicAuthHeaderTrait = new class() {
    use GetBasicAuthHeaderTrait;

    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @return array{headers: array<string, string>}
     */
    public function output(string $hatenaId, string $apiKey): array
    {
        return $this->getBasicAuthHeader($hatenaId, $apiKey);
    }
};

it('tests GetBasicAuthHeaderTrait performs correctly', function () use ($classUsingGetBasicAuthHeaderTrait) {
    $hatenaId = 'foo0318';
    $apiKey = 'bar0318';
    expect($classUsingGetBasicAuthHeaderTrait->output($hatenaId, $apiKey))
        ->toMatchArray([
            'headers' => [
                'Content-Type' => 'application/atomsvc+xml; charset=utf-8',
                'Authorization' => 'Basic Zm9vMDMxODpiYXIwMzE4',
            ]
        ]);
});