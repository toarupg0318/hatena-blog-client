<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns;

trait GetWSSEAuthHeaderTrait
{
    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @return array{
     *     headers: array<string, string>
     * }
     */
    private function getWSSEAuthHeader(
        string $hatenaId,
        string $apiKey
    ): array {
        $nonce = hash(
            algo: 'sha1',
            data: hash(
                algo: 'sha1',
                data: strval(time()) . uniqid(strval(mt_rand()), true) . rand() . intval(getmypid())
            )
        );
        $now = date('Y-m-d\TH:i:s\Z');
        $digest = base64_encode(
            string: hash(
                algo: 'sha1',
                data: $nonce . $now . $apiKey,
                binary: true
            )
        );

        $credentials = sprintf(
            'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s',
            $hatenaId,
            $digest,
            base64_encode($nonce),
            $now
        );

        return [
            'headers' => [
                'Accept' => 'application/x.atom+xml, application/xml, text/xml, */*',
                'X-WSSE' => $credentials,
            ]
        ];
    }
}