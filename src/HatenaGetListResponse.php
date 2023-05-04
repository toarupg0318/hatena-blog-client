<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\Concerns\RecursiveSearchWithKeyValueTrait;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaGetListResponseInterface;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaHttpException;

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

    /** @var array<string, mixed> $parsedData */
    private static array $parsedData;

    /**
     * @param ResponseInterface $response
     * @throws HatenaHttpException
     */
    public function __construct(
        public readonly ResponseInterface $response
    ) {
        parent::__construct();

        $jsonEncodedData = json_encode(
            value: simplexml_load_string(
                data: $this->response->getBody()->getContents()
            )
        );
        if ($jsonEncodedData === false) {
            throw new HatenaHttpException();
        }

        $jsonDecodedData = json_decode(
            json: $jsonEncodedData,
            associative: true
        );
        unset($jsonEncodedData);
        if (! is_array($jsonDecodedData)) {
            throw new HatenaHttpException();
        }

        self::$parsedData = $jsonDecodedData;
    }

    /**
     * Get array-decoded whole data.
     *
     * @return array<string, mixed>
     */
    public function getParsedData(): array
    {
        return self::$parsedData;
    }

    /**
     * Get first page URL from fetched response.
     *
     * @return string|null
     */
    public function getFirstPageUrl(): string|null
    {
        return $this->recursiveSearchWithKeyValue(
            arrayToSearch: self::$parsedData,
            searchKey: 'rel',
            searchValue: 'first',
            siblingKey: 'href',
        );
    }

    /**
     * Get next page URL from fetched response.
     *
     * @return string|null
     */
    public function getNextPageUrl(): string|null
    {
        return $this->recursiveSearchWithKeyValue(
            arrayToSearch: self::$parsedData,
            searchKey: 'rel',
            searchValue: 'next',
            siblingKey: 'href',
        );
    }

    /**
     * Extract human-like structure from Get List response.
     *
     * @return ParsedEntries[]
     */
    public function getParsedEntries(): array
    {
        return self::extractEntriesFromGetListResponse(self::$parsedData);
    }

    /**
     * @param array<string, mixed> $parsedData
     * @return ParsedEntries[]
     */
    private function extractEntriesFromGetListResponse(array $parsedData): array
    {
        $entries = $parsedData['entry'] ?? [];
        if (! is_array($entries) || count($entries) < 1) {
            return [];
        }

        return array_map(function (array $entry) {
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