<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Toarupg0318\HatenaBlogClient\Concerns\GetParsedDataFromResponseContentTrait;
use Toarupg0318\HatenaBlogClient\Concerns\RecursiveSearchWithKeyValueTrait;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaGetListResponseInterface;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;

/**
 * @phpstan-type ParsedEntries = array{
 *     entryId?: string,
 *     editLinkUrl?: string|null,
 *     authorName?: string|null,
 *     title?: string|null,
 *     updated?: string|null,
 *     published?: string|null,
 *     summary?: string|null,
 *     categories?: string[]
 * }
 */
final class HatenaGetListResponse extends Response implements ResponseInterface, HatenaGetListResponseInterface
{
    use RecursiveSearchWithKeyValueTrait;
    use GetParsedDataFromResponseContentTrait;

    private readonly StreamInterface $stream;

    /** @var array<string, mixed>|null $parsedData */
    private array|null $parsedData = null;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response) {;
        parent::__construct(
            status: $response->getStatusCode(),
            headers: $response->getHeaders(),
            body: $response->getBody(),
            version: $response->getProtocolVersion(),
            reason: $response->getReasonPhrase()
        );
        $this->stream = $response->getBody();
    }

    /**
     * Get array-decoded whole data.
     *
     * @return array<string, mixed>
     *
     * @throws HatenaUnexpectedException
     */
    public function getParsedData(): array
    {
        if ($this->parsedData === null) {
            $parsedData = $this->getParsedDataFromResponseContent(
                $this->stream->getContents()
            );
            $this->parsedData = $parsedData;
        }

        return $this->parsedData;
    }

    /**
     * Get first page URL from fetched response.
     *
     * @return string|null
     *
     * @throws HatenaUnexpectedException
     */
    public function getFirstPageUrl(): string|null
    {
        if ($this->parsedData === null) {
            $parsedData = $this->getParsedDataFromResponseContent(
                $this->stream->getContents()
            );
            $this->parsedData = $parsedData;
        }

        return $this->recursiveSearchWithKeyValue(
            arrayToSearch: $this->parsedData,
            searchKey: 'rel',
            searchValue: 'first',
            siblingKey: 'href',
        );
    }

    /**
     * Get next page URL from fetched response.
     *
     * @return string|null
     *
     * @throws HatenaUnexpectedException
     */
    public function getNextPageUrl(): string|null
    {
        if ($this->parsedData === null) {
            $parsedData = $this->getParsedDataFromResponseContent(
                $this->stream->getContents()
            );
            $this->parsedData = $parsedData;
        }

        return $this->recursiveSearchWithKeyValue(
            arrayToSearch: $this->parsedData,
            searchKey: 'rel',
            searchValue: 'next',
            siblingKey: 'href',
        );
    }

    /**
     * Extract human-like structure from Get List response.
     *
     * @return ParsedEntries[]
     * @throws HatenaUnexpectedException
     */
    public function getParsedEntries(): array
    {
        if ($this->parsedData === null) {
            $parsedData = $this->getParsedDataFromResponseContent(
                $this->stream->getContents()
            );
            $this->parsedData = $parsedData;
        }

        return self::extractEntriesFromGetListResponse($this->parsedData);
    }

    /**
     * @internal
     *
     * @param array<string, mixed> $parsedData
     * @return ParsedEntries[]
     */
    private function extractEntriesFromGetListResponse(array $parsedData): array
    {
        $entries = $parsedData['entry'] ?? [];
        if (! is_array($entries) || count($entries) < 1) {
            return [];
        }

        return array_map(function (array $entry): array {
            $return = [];

            // extract entry id
            // https://blog.hatena.ne.jp/hoge001/fuga002.hatenablog.com/atom/entry/4207575166455635809
            // to
            // 4207575166455635809
            $editUri = $this->recursiveSearchWithKeyValue($entry, 'rel', 'edit', 'href');
            if (is_string($editUri)) {
                $return['editLinkUrl'] = $editUri;
                preg_match('/^(.*)(entry\/)(.*)$/u', $editUri, $matches);
                $entryId = $matches[3] ?? null;
                if (! empty($entryId) && is_string($entryId)) {
                    $return['entryId'] = $matches[3];
                }
            }

            $authorName = $entry['author']['name'];
            if ($authorName !== null) {
                $return['authorName'] = $authorName;
            }

            $title = $entry['title'] ?? null;
            if ($title !== null) {
                $return['title'] = $title;
            }

            $updated = $entry['updated'] ?? null;
            if ($updated !== null) {
                $return['updated'] = $updated;
            }

            $published = $entry['published'] ?? null;
            if ($published !== null) {
                $return['published'] = $published;
            }

            $summary = $entry['summary'] ?? null;
            if ($summary !== null) {
                $return['summary'] = $summary;
            }

            $rawCategories = $entry['category'] ?? [];
            if (is_array($rawCategories)) {
                $categories = [];

                $rawCategoryCount = count($rawCategories);
                if ($rawCategoryCount === 0) {
//                    return [];
                } elseif ($rawCategoryCount === 1) {
                    if ($rawCategories['@attributes']['term'] ?? null) {
                        $categories[] = $rawCategories['@attributes']['term'];
                    }
                } else {
                    foreach ($rawCategories as $rawCategory) {
                        if ($rawCategory['@attributes']['term'] ?? null) {
                            $categories[] = $rawCategory['@attributes']['term'];
                        }
                    }
                }

                $return['categories'] = $categories;
            }

            return $return;
        }, $entries);
    }
}