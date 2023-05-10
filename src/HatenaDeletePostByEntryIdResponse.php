<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Toarupg0318\HatenaBlogClient\Concerns\GetParsedDataFromResponseContentTrait;
use Toarupg0318\HatenaBlogClient\Contracts\HatenaResponses\HatenaDeletePostByEntryIdResponseInterface;
use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;

final class HatenaDeletePostByEntryIdResponse extends Response implements ResponseInterface, HatenaDeletePostByEntryIdResponseInterface
{
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
}