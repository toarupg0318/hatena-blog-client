<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Toarupg0318\HatenaBlogClient\Concerns\GetParsedDataFromResponseContentTrait;
use Toarupg0318\HatenaBlogClient\Concerns\RecursiveSearchWithKeyValueTrait;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaPostResponseInterface;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaHttpException;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;

final class HatenaPostResponse extends Response implements ResponseInterface, HatenaPostResponseInterface
{
    use RecursiveSearchWithKeyValueTrait;
    use GetParsedDataFromResponseContentTrait;

    private readonly StreamInterface $stream;

    /** @var array<string, mixed>|null $parsedData */
    private array|null $parsedData = null;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response) {
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
     * @return string
     *
     * @throws HatenaUnexpectedException
     * @throws HatenaHttpException
     */
    public function getEntryId(): string
    {
        if ($this->parsedData === null) {
            $parsedData = $this->getParsedDataFromResponseContent(
                $this->stream->getContents()
            );
            $this->parsedData = $parsedData;
        }

        // raw value is something like
        // "tag:blog.hatena.ne.jp,2013:blog-foo012-4207112889979077890-4207575160648544270"
        // in this case, "4207575160648544270" is the value to extract
        $rawId = $this->parsedData['id'] ?? null;
        if (! is_string($rawId)) {
            throw new HatenaHttpException('Failed to retrieve entry id.');
        }

        $separatedValues = explode('-', $rawId);
        $index = count($separatedValues) - 1;
//        if ($index < 0) {
//            throw new HatenaHttpException('Failed to retrieve entry id.');
//        }

        $entryId = $separatedValues[$index];
        if (! is_string($entryId)) {
            throw new HatenaHttpException('Failed to retrieve entry id.');
        }

        return $entryId;
    }
}