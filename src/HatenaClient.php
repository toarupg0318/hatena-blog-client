<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaClientInterface;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMDocument;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMElement;

/**
 * @implements HatenaClientDumper<HatenaClientInterface>
 */
class HatenaClient implements HatenaClientInterface, HatenaClientDumper
{
    private Client|null $client;

    private static int|null $memoizedValue = null;

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
        if (! isset($this->client) || empty(self::$memoizedValue)) {
            $this->client = new Client();
        }
    }

    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @param 'basic'|'wsse'|'oauth' $auth
     * @return HatenaClientInterface
     */
    public static function getInstance(
        string $hatenaId,
        string $apiKey,
        string $auth = 'basic'
    ): HatenaClientInterface {
        static $hatenaClient;

        if (empty($hatenaClient) || empty(self::$memoizedValue)) {
            self::$memoizedValue = self::calcMemo($hatenaId, $apiKey, $auth);
            $hatenaClient = new self(
                $hatenaId,
                $apiKey,
                $auth
            );
            return $hatenaClient;
        }

        $digest = crc32(json_encode([$hatenaId, $apiKey, $auth]));
        if ($digest === self::$memoizedValue || ! $hatenaClient instanceof HatenaClientInterface) {
            return $hatenaClient;
        }

//        return new self(
//            $hatenaId,
//            $apiKey,
//            $auth
//        );

        // todo:
        throw new \Exception();
    }

    /**
     * @param string|null $page
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function getList(string|null $page = null): ResponseInterface
    {
        if (! isset($this->client)) {
            throw new \Exception(); // todo:
        }

        $header = match ($this->auth) {
            'basic' => self::getBasicHeader($this->hatenaId, $this->apiKey),    // todo: trait
            'wsse' => self::getWSSEHeader($this->hatenaId, $this->apiKey),      // todo: trait
        };

        return $this->client
            ->get(
                uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaId}.hatenablog.com/atom/entry",
                options: $header
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
     *
     *
     * @param string|HatenaDOMDocument<int, HatenaDOMElement> $content
     * @param string $title
     * @param self::CONTENT_TYPE_* $contentType
     * @param bool $draft
     * @param string|null $updated
     * @param string|null $customUrl
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function post(
        string|HatenaDOMDocument $content,
        string                   $title,
        string                   $contentType,
        bool                     $draft,
        string|null              $updated,
        string|null              $customUrl
    ): ResponseInterface {
        if (! isset($this->client)) {
            throw new \Exception(); // todo: make ex
        }

        $postValue = ($content instanceof HatenaDOMDocument) ? $content->__toString() : $content;

        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom"
       xmlns:app="http://www.w3.org/2007/app">
  <title>wsse 認証</title>
  <author><name>name</name></author>
  <content type="text/plain">
*大見出し記法
**中見出し記法
$content
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
            uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaId}.hatenablog.com/atom/entry",
            options: $postData
        );
    }

    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @param string $auth
     * @return int
     * @throws \Exception
     */
    private static function calcMemo(string $hatenaId, string $apiKey, string $auth): int
    {
        $encodedValue = json_encode([$hatenaId, $apiKey, $auth]);
        if (! is_string($encodedValue)) {
            throw new \Exception(); // todo: HatenaClientUnexpectedException
        }

        return crc32($encodedValue);
    }

    /**
     * @return HatenaClientInterface
     */
    public function dump(): HatenaClientInterface
    {
        dump($this);
        return $this;
    }

    /**
     * @return never
     */
    public function dd(): never
    {
        dd($this);
    }
}