<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\HatenaClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\Concerns\DOM\HatenaDOMDocument;

class HatenaClient
    //implements HatenaClientInterface
{
    private Client|null $client;

    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @param 'basic'|'wsse'|'oauth' $auth
     */
    private function __construct(
        private readonly string $hatenaId,
        private readonly string $apiKey,
        private readonly string $auth = 'basic'
    ) {
        if (! isset($this->client)) {
            $this->client = new Client();
        }

        // todo: crc32でnewするか判定

        return $this;
    }

    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @param 'basic'|'wsse'|'oauth' $auth
     * @return self
     */
    public static function getInstance(
        string $hatenaId,
        string $apiKey,
        string $auth = 'basic'
    ): self {
        return new self(
            $hatenaId,
            $apiKey,
            $auth
        );
    }

    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @return array{
     *     header: array<string, string>,
     *     auth: array{0: string, 1: string}
     * }
     */
    private function getBasicHeader(
        string $hatenaId,
        string $apiKey
    ): array {
        return [
            'headers' => [
                'Content-Type' => 'application/atomsvc+xml; charset=utf-8',
            ],
            'auth' => [$hatenaId, $apiKey]
        ];
    }

    /**
     * todo: trait
     * @param string $hatenaId
     * @param string $apiKey
     * @return array{
     *     headers: array{
     *         Accept: string,
     *         X-WSSE: string
     *     }
     * }
     */
    public function getWSSEHeader(
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

    /**
     * @param string|HatenaDOMDocument $document
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function post(string|HatenaDOMDocument $document): ResponseInterface
    {
        $postValue = ($document instanceof HatenaDOMDocument) ? $document->__toString() : $document;

        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom"
       xmlns:app="http://www.w3.org/2007/app">
  <title>wsse 認証</title>
  <author><name>name</name></author>
  <content type="text/plain">
*大見出し記法
**中見出し記法
$document
  </content>
  <updated>2008-01-01T00:00:00</updated>
  <category term="Scala" />
  <app:control>
    <app:draft>{yes | no}</app:draft>
  </app:control>
  <hatenablog:custom-url xmlns:hatenablog="http://www.hatena.ne.jp/info/xmlns#hatenablog">2008-happy-new-year</hatenablog:custom-url>
</entry>
XML;

        $header = match ($this->auth) {
            'basic' => self::getBasicHeader($this->hatenaId, $this->apiKey),    // todo: trait
            'wsse' => self::getWSSEHeader($this->hatenaId, $this->apiKey),      // todo: trait
        };

        $postData = array_merge(
            $header,
            [
                'body' => $xml,
            ]
        );

        return $this->client->post(
            uri: 'https://blog.hatena.ne.jp/toarupg0318/toarupg0318.hatenablog.com/atom/entry',
            options: $postData
        );
    }
}