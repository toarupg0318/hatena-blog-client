<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\Concerns\ExtractPageValueFromLinkTrait;
use Toarupg0318\HatenaBlogClient\Concerns\GetBasicAuthHeaderTrait;
use Toarupg0318\HatenaBlogClient\Concerns\GetWSSEAuthHeaderTrait;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaClientDumper;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaClientInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaDeletePostByEntryIdResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaEditPostByEntryIdResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaGetListResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaGetPostByEntryIdResponseInterface;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaPostResponseInterface;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaHttpException;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaInvalidArgumentException;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;
use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaDOMElement;
use Toarupg0318\HatenaBlogClient\ValueObjects\HatenaDOMDocument;

/**
 * @implements HatenaClientDumper<HatenaClientInterface>
 */
class HatenaClient implements HatenaClientInterface, HatenaClientDumper
{
    use GetBasicAuthHeaderTrait;
    use GetWSSEAuthHeaderTrait;
    use ExtractPageValueFromLinkTrait;

    private Client|null $client = null;

    private static int|null $memoizedValue = null;

    /**
     * @param 'basic'|'wsse' $auth
     */
    private function __construct(
        private readonly string $hatenaId,
        private readonly string $hatenaBlogId,
        private readonly string $apiKey,
        private readonly string $auth = 'basic'
    ) {
        if (! isset($this->client) || empty(self::$memoizedValue)) {
            $this->client = new Client();
        }
    }

    /**
     * @param 'basic'|'wsse' $auth
     *
     * @throws Exception
     */
    public static function getInstance(
        string $hatenaId,
        string $hatenaBlogId,
        string $apiKey,
        string $auth = 'basic'
    ): self {
        static $hatenaClient;

        if (empty($hatenaClient) || empty(self::$memoizedValue)) {
            self::$memoizedValue = self::calcMemo($hatenaId, $hatenaBlogId, $apiKey, $auth);
            $hatenaClient = new self(
                hatenaId: $hatenaId,
                hatenaBlogId: $hatenaBlogId,
                apiKey: $apiKey,
                auth: $auth
            );
            return $hatenaClient;
        }

        $encodedValue = json_encode([$hatenaId, $hatenaBlogId, $apiKey, $auth], JSON_THROW_ON_ERROR);

        $checkSum = crc32($encodedValue);
        if ($checkSum !== self::$memoizedValue) {
            $hatenaClient = new self($hatenaId, $hatenaBlogId, $apiKey, $auth);
        }
        return $hatenaClient;
    }

    /**
     * @param string|null $page 'https://blog.hatena.ne.jp/hoge0318/fuga0318.hatenablog.com/atom/entry?page=1780929531' or '1780929531'
     * @return ResponseInterface&HatenaGetListResponseInterface
     *
     * @throws HatenaHttpException
     * @throws HatenaUnexpectedException
     * @throws HatenaInvalidArgumentException
     *
     * @example
     *  $client->getList('https://blog.hatena.ne.jp/hoge0318/fuga0318.hatenablog.com/atom/entry?page=1780929531');
     * @example
     *  $client->getList('1780929531');
     * @example
     *  $client->getList();
     */
    public function getList(string|null $page = null): ResponseInterface&HatenaGetListResponseInterface
    {
        if (! isset($this->client)) {
            throw new HatenaUnexpectedException();
        }

        $header = match ($this->auth) {
            'basic' => self::getBasicAuthHeader($this->hatenaId, $this->apiKey),
            'wsse' => self::getWSSEAuthHeader($this->hatenaId, $this->apiKey),
//            'oauth' => throw new \LogicException(),
        };

        try {
            $response = $this->client
                ->get(
                    uri: ($page === null)
                        ? "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaBlogId}/atom/entry"
                        : "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaBlogId}/atom/entry?page={$this->extractPageValueFromLink($page)}",
                    options: $header
                );
            return new HatenaGetListResponse($response);
        } catch (HatenaInvalidArgumentException $hatenaInvalidArgumentException) {
            throw new HatenaInvalidArgumentException(
                message: 'Passed page value is invalid.',
                code: $hatenaInvalidArgumentException->getCode(),
                previous: $hatenaInvalidArgumentException
            );
        } catch (GuzzleException $guzzleException) {
            throw new HatenaHttpException(
                code: $guzzleException->getCode(),
                previous: $guzzleException
            );
        }
    }

    /**
     * @param string $entryId it comes from getList.entryId
     * @return ResponseInterface&HatenaGetPostByEntryIdResponseInterface
     *
     * @throws HatenaUnexpectedException
     * @throws HatenaHttpException
     * @throws HatenaInvalidArgumentException
     */
    public function getPostByEntryId(
        string $entryId
    ): ResponseInterface&HatenaGetPostByEntryIdResponseInterface {
        if (empty($entryId)) {
            throw new HatenaInvalidArgumentException('Entry id is empty.');
        }

        if (! isset($this->client)) {
            throw new HatenaUnexpectedException();
        }

        $header = match ($this->auth) {
            'basic' => self::getBasicAuthHeader($this->hatenaId, $this->apiKey),
            'wsse' => self::getWSSEAuthHeader($this->hatenaId, $this->apiKey),
//            'oauth' => throw new \LogicException(),
        };

        try {
            $response = $this->client
                ->get(
                    uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaBlogId}/atom/entry/{$entryId}",
                    options: $header
                );
            return new HatenaGetPostByEntryIdResponse($response);
        } catch (GuzzleException $guzzleException) {
            throw new HatenaHttpException(
                message: $guzzleException->getMessage(),
                code: $guzzleException->getCode(),
                previous: $guzzleException
            );
        }
    }

    /**
     * @param string|HatenaDOMDocument<int, HatenaDOMElement> $content
     * @param string|null $title
     * @param bool $draft
     * @param string|null $updated
     * @param string|null $customUrl
     * @param string[] $categories
     * @return ResponseInterface&HatenaPostResponseInterface
     *
     * @throws HatenaUnexpectedException
     * @throws HatenaHttpException
     */
    public function post(
        string|HatenaDOMDocument $content,
        string|null $title = null,
        bool $draft = true,
        string|null $updated = null,
        string|null $customUrl = null,
        array $categories = []
    ): ResponseInterface&HatenaPostResponseInterface {
        if (! isset($this->client)) {
            throw new HatenaUnexpectedException();
        }

        $contentInXML = htmlspecialchars(
            ($content instanceof HatenaDOMDocument) ? $content->__toString() : $content
        );
        $draftYesOrNo = ($draft) ? 'yes' : 'no';
        $categoriesImXml = implode(
            separator: '',
            array: array_map(
                callback: fn(string $categoryValue) => "<category term=\"{$categoryValue}\" />",
                array: $categories
            )
        );

        $bodyToPost = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom"
       xmlns:app="http://www.w3.org/2007/app">
  <title>{$title}</title>
  <author><name>name</name></author>
  <content type="text/plain">
$contentInXML
  </content>
  <updated>{$updated}</updated>
  $categoriesImXml
  <app:control>
    <app:draft>{$draftYesOrNo}</app:draft>
  </app:control>
  <hatenablog:custom-url xmlns:hatenablog="http://www.hatena.ne.jp/info/xmlns#hatenablog">
    {$customUrl}  
  </hatenablog:custom-url>
</entry>
XML;

        $header = match ($this->auth) {
            'basic' => self::getBasicAuthHeader($this->hatenaId, $this->apiKey),
            'wsse' => self::getWSSEAuthHeader($this->hatenaId, $this->apiKey),
        };

        $postData = [...$header, 'body' => $bodyToPost];

        try {
            $response = $this->client->post(
                uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaBlogId}/atom/entry",
                options: $postData
            );
            return new HatenaPostResponse($response);
        } catch (GuzzleException $guzzleException) {
            throw new HatenaHttpException(
                message: $guzzleException->getMessage(),
                code: $guzzleException->getCode(),
                previous: $guzzleException
            );
        }
    }

    /**
     * @param string $entryId
     * @param string|HatenaDOMDocument<int, HatenaDOMElement> $content
     * @param string|null $title if empty, constant "■" is embedded.
     * @param bool $draft
     * @param string|null $updated
     * @param string|null $customUrl
     * @param string[] $categories
     * @return HatenaEditPostByEntryIdResponseInterface&ResponseInterface
     *
     * @throws HatenaUnexpectedException
     * @throws HatenaInvalidArgumentException
     * @throws HatenaHttpException
     */
    public function edit(
        string $entryId,
        HatenaDOMDocument|string $content,
        ?string $title = null,
        bool $draft = true,
        ?string $updated = null,
        ?string $customUrl = null,
        array $categories = []
    ): ResponseInterface&HatenaEditPostByEntryIdResponseInterface {
        if (empty($entryId)) {
            throw new HatenaInvalidArgumentException('Entry id is empty.');
        }

        if (! isset($this->client)) {
            throw new HatenaUnexpectedException();
        }

        $contentInXML = htmlspecialchars(
            ($content instanceof HatenaDOMDocument) ? $content->__toString() : $content
        );
        $draftYesOrNo = ($draft) ? 'yes' : 'no';
        $categoriesImXml = implode(
            separator: '',
            array: array_map(
                callback: fn(string $categoryValue) => "<category term=\"{$categoryValue}\" />",
                array: $categories
            )
        );

        $bodyToPost = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom"
       xmlns:app="http://www.w3.org/2007/app">
  <title>{$title}</title>
  <author><name>name</name></author>
  <content type="text/plain">
$contentInXML
  </content>
  <updated>{$updated}</updated>
  $categoriesImXml
  <app:control>
    <app:draft>{$draftYesOrNo}</app:draft>
  </app:control>
  <hatenablog:custom-url xmlns:hatenablog="http://www.hatena.ne.jp/info/xmlns#hatenablog">
    {$customUrl}  
  </hatenablog:custom-url>
</entry>
XML;

        $header = match ($this->auth) {
            'basic' => self::getBasicAuthHeader($this->hatenaId, $this->apiKey),
            'wsse' => self::getWSSEAuthHeader($this->hatenaId, $this->apiKey),
        };

        $postData = [...$header, 'body' => $bodyToPost];

        try {
            $response = $this->client->put(
                uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaBlogId}/atom/entry/{$entryId}",
                options: $postData
            );
            return new HatenaEditPostByEntryIdResponse($response);
        } catch (GuzzleException $guzzleException) {
            throw new HatenaHttpException(
                message: $guzzleException->getMessage(),
                code: $guzzleException->getCode(),
                previous: $guzzleException
            );
        }
    }

    /**
     * @param string $entryId it comes from getList.entryId
     * @return ResponseInterface&HatenaDeletePostByEntryIdResponseInterface
     *
     * @throws HatenaHttpException
     * @throws HatenaUnexpectedException
     * @throws HatenaInvalidArgumentException
     */
    public function deletePostByEntryId(
        string $entryId
    ): ResponseInterface&HatenaDeletePostByEntryIdResponseInterface {
        if (! isset($this->client)) {
            throw new HatenaUnexpectedException();
        }

        if (empty($entryId)) {
            throw new HatenaInvalidArgumentException('Entry id is empty.');
        }

        $header = match ($this->auth) {
            'basic' => self::getBasicAuthHeader($this->hatenaId, $this->apiKey),
            'wsse' => self::getWSSEAuthHeader($this->hatenaId, $this->apiKey),
        };

        try {
            $response = $this->client->delete(
                uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaBlogId}/atom/entry/{$entryId}",
                options: $header
            );
            return new HatenaDeletePostByEntryIdResponse($response);
        } catch (GuzzleException $guzzleException) {
            throw new HatenaHttpException(
                message: $guzzleException->getMessage(),
                code: $guzzleException->getCode(),
                previous: $guzzleException
            );
        }
    }

    /**
     * @internal
     *
     *
     * @throws HatenaUnexpectedException
     */
    private static function calcMemo(
        string $hatenaId,
        string $hatenaBlogId,
        string $apiKey,
        string $auth
    ): int {
        $encodedValue = json_encode([$hatenaId, $hatenaBlogId, $apiKey, $auth], JSON_THROW_ON_ERROR);
        if (! is_string($encodedValue)) {
            throw new HatenaUnexpectedException();
        }

        return crc32($encodedValue);
    }

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