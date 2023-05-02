<?php

use Toarupg0318\HatenaBlogClient\Concerns\GetWSSEAuthHeaderTrait;

$classUsingGetWSSEAuthHeaderTrait = new class() {
    use GetWSSEAuthHeaderTrait;

    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @return array{
     *     headers: array<string, string>
     * }
     */
    public function output(
        string $hatenaId,
        string $apiKey
    ): array {
        return $this->getWSSEAuthHeader($hatenaId, $apiKey);
    }
};

it('tests GetWSSEAuthHeaderTrait performs correctly', function () use ($classUsingGetWSSEAuthHeaderTrait) {
    $hatenaId = 'hoge0318';
    $apiKey = 'fuga0318';
    $output = $classUsingGetWSSEAuthHeaderTrait->output($hatenaId, $apiKey);
    expect($output)
        ->toHaveKeys([
            'headers',
            'headers.Accept',
            'headers.X-WSSE',
        ]);
});

it('tests output header.Accept value is correct', function () use ($classUsingGetWSSEAuthHeaderTrait) {
    $hatenaId = 'hoge0318';
    $apiKey = 'fuga0318';
    expect($classUsingGetWSSEAuthHeaderTrait->output($hatenaId, $apiKey)['headers']['Accept'] ?? null)
        ->toBe('application/x.atom+xml, application/xml, text/xml, */*');
});

it('tests output header.X-WSSE value is correct', function () use ($classUsingGetWSSEAuthHeaderTrait) {
    $hatenaId = 'hoge0318';
    $apiKey = 'fuga0318';
    expect($classUsingGetWSSEAuthHeaderTrait->output($hatenaId, $apiKey)['headers']['X-WSSE'] ?? null)
        ->toBeString();
});