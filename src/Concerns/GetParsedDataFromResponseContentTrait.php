<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\Concerns;

use Toarupg0318\HatenaBlogClient\Exceptions\HatenaUnexpectedException;

/**
 * @internal
 */
trait GetParsedDataFromResponseContentTrait
{
    /**
     * Convert XML to array.
     *
     * @return array<string, mixed>
     * @throws HatenaUnexpectedException
     */
    private function getParsedDataFromResponseContent(string $content): array
    {
        $xmlElement = simplexml_load_string(data: $content);
        if ($xmlElement === false) {
            throw new HatenaUnexpectedException('Failed to process the response from Hatena.');
        }

        $jsonEncodedData = json_encode(value: $xmlElement);
        if ($jsonEncodedData === false) {
            throw new HatenaUnexpectedException('Failed to process the response from Hatena.');
        }

        $jsonDecodedData = json_decode(
            json: $jsonEncodedData,
            associative: true
        );
        unset($jsonEncodedData);
        if (! is_array($jsonDecodedData)) {
            throw new HatenaUnexpectedException('Failed to process the response from Hatena.');
        }

        return $jsonDecodedData;
    }
}