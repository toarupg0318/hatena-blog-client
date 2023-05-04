<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Toarupg0318\HatenaBlogClient\Concerns\RecursiveSearchWithKeyValueTrait;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaDeletePostByEntryIdResponseInterface;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaHttpException;

final class HatenaDeletePostByEntryIdResponse extends Response implements ResponseInterface, HatenaDeletePostByEntryIdResponseInterface
{
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
        if ($jsonEncodedData === false || $jsonEncodedData === 'false') {
            // Only delete is empty response
            self::$parsedData = [];
            return;
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
}