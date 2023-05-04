<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\Concerns\RecursiveSearchWithKeyValueTrait;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaGetListResponseInterface;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaHttpException;

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
}