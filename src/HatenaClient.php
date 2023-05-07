<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
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

    private Client|null $client;

    private static int|null $memoizedValue = null;

    /**
     * @param string $hatenaId
     * @param string $apiKey
     * @param 'basic'|'wsse' $auth
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
     * @param 'basic'|'wsse' $auth
     * @return self
     *
     * @throws Exception
     */
    public static function getInstance(
        string $hatenaId,
        string $apiKey,
        string $auth = 'basic'
    ): self {
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

        $encodedValue = json_encode([$hatenaId, $apiKey, $auth]);
        if ($encodedValue === false) {
            throw new HatenaUnexpectedException();
        }

        $checkSum = crc32($encodedValue);
        if ($checkSum === self::$memoizedValue) {
            return $hatenaClient;
        }

        throw new HatenaUnexpectedException();
    }

    /**
     * @param string|null $page 'https://blog.hatena.ne.jp/hoge0318/fuga0318.hatenablog.com/atom/entry?page=1780929531' or '1780929531'
     * @return ResponseInterface&HatenaGetListResponseInterface
     *
     * @throws HatenaHttpException
     * @throws HatenaUnexpectedException
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
                    uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaId}.hatenablog.com/atom/entry",
                    options: $header
                );
            return new HatenaGetListResponse($response);
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
                    uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaId}.hatenablog.com/atom/entry/{$entryId}",
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
     * @param HatenaClientInterface::CONTENT_TYPE_* $contentType
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
        string $contentType = 'text/plain',
        bool $draft = true,
        string|null $updated = null,
        string|null $customUrl = null,
        array $categories = []
    ): ResponseInterface&HatenaPostResponseInterface {
        if (! isset($this->client)) {
            throw new HatenaUnexpectedException();
        }

        $contentInXML = ($content instanceof HatenaDOMDocument) ? $content->__toString() : $content;
        $draftYesOrNo = ($draft) ? 'yes' : 'no';
        $categoriesImXml = implode(
            separator: '',
            array: array_map(
                callback: function (string $categoryValue) {
                    return "<category term=\"{$categoryValue}\" />";
                },
                array: $categories
            )
        );

        $bodyToPost = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom"
       xmlns:app="http://www.w3.org/2007/app">
  <title>{$title}</title>
  <author><name>name</name></author>
  <content type="$contentType">
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

        $postData = array_merge(
            $header,
            [
                'body' => $bodyToPost,
            ]
        );

        try {
            $response = $this->client->post(
                uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaId}.hatenablog.com/atom/entry",
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
     * @param self::CONTENT_TYPE_* $contentType
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
        string $contentType = 'text/plain',
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

        $contentInXML = ($content instanceof HatenaDOMDocument) ? $content->__toString() : $content;
        $draftYesOrNo = ($draft) ? 'yes' : 'no';
        $categoriesImXml = implode(
            separator: '',
            array: array_map(
                callback: function (string $categoryValue) {
                    return "<category term=\"{$categoryValue}\" />";
                },
                array: $categories
            )
        );

        $bodyToPost = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom"
       xmlns:app="http://www.w3.org/2007/app">
  <title>{$title}</title>
  <author><name>name</name></author>
  <content type="$contentType">
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

        $postData = array_merge(
            $header,
            [
                'body' => $bodyToPost,
            ]
        );

        try {
            $response = $this->client->put(
                uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaId}.hatenablog.com/atom/entry/{$entryId}",
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
                uri: "https://blog.hatena.ne.jp/{$this->hatenaId}/{$this->hatenaId}.hatenablog.com/atom/entry/{$entryId}",
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
     * @param string $hatenaId
     * @param string $apiKey
     * @param string $auth
     * @return int
     * @throws HatenaUnexpectedException
     */
    private static function calcMemo(string $hatenaId, string $apiKey, string $auth): int
    {
        $encodedValue = json_encode([$hatenaId, $apiKey, $auth]);
        if (! is_string($encodedValue)) {
            throw new HatenaUnexpectedException();
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