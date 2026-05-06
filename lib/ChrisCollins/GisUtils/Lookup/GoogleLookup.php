<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Lookup;

use ChrisCollins\GeneralUtils\Curl\CurlHandle;
use ChrisCollins\GeneralUtils\Exception\JsonException;
use ChrisCollins\GeneralUtils\Json\JsonCodec;
use ChrisCollins\GisUtils\Address\Address;
use ChrisCollins\GisUtils\Coordinate\LatLong;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Exception\AddressNotFoundException;
use ChrisCollins\GisUtils\Exception\GoogleGeocoderException;

/**
 * GoogleLookup
 *
 * A class to lookup addresss via Google.
 *
 * Note, according to Google's terms of service, this service should only be used if the intention is to use the data
 * returned to display points on a Google map.
 */
class GoogleLookup implements LookupInterface
{
    /** @var string Constant for the datum that coordinates returned by the service use. */
    public const SERVICE_DATUM = DatumFactory::DATUM_WGS84;

    /** @var string Constant for the base URL (without the scheme) for the service. */
    private const SERVICE_BASE_URL = 'maps.googleapis.com/maps/api/geocode/json';

    /** @var bool Whether or not HTTPS requests should be made. */
    private bool $useHttps = true;

    public function __construct(
        private readonly DatumFactory $datumFactory,
        private readonly CurlHandle $curlHandle,
        private readonly JsonCodec $jsonCodec
    ) {
    }

    /**
     * Obtain a LatLng from an address.
     *
     * @throws AddressNotFoundException If the address is not found.
     * @throws GoogleGeocoderException If there was an issue with the Google Geocoder service.
     * @throws JsonException If there was an issue with the format of the JSON.
     */
    public function addressToLatLong(Address $address): LatLong
    {
        $url = $this->getServiceUrl($address);
        $json = $this->makeRequest($url);

        return $this->parseServiceResponse($json);
    }

    /**
     * Parse the response from the service, creating a LatLong instance from it if possible.
     *
     * @throws AddressNotFoundException If the address was not found.
     * @throws JsonException If the JSON was unable to be decoded.
     */
    private function parseServiceResponse(string $json): LatLong
    {
        /** @var array<string,mixed> $decoded */
        $decoded = $this->jsonCodec->decode($json, true);

        $latLong = null;

        if (isset($decoded['results'][0]['geometry']['location'])) {
            $location = $decoded['results'][0]['geometry']['location'];

            if (isset($location['lat'], $location['lng'])) {
                $latLong = new LatLong(
                    $location['lat'],
                    $location['lng'],
                    null,
                    $this->datumFactory->create(self::SERVICE_DATUM)
                );
            }
        }

        if (!$latLong instanceof LatLong) {
            throw new AddressNotFoundException('Unable to find coordinates for address.');
        }

        return $latLong;
    }

    /**
     * Make a request to the service and get the response JSON.
     */
    private function makeRequest(string $url): string
    {
        $this->curlHandle->initialise($url);
        $responseContent = $this->curlHandle->execute();

        if ($this->curlHandle->getErrorCode() !== null) {
            $error = 'Error performing geocoding: ' . $this->curlHandle->getErrorMessage() . '.';

            throw new GoogleGeocoderException($error);
        }

        return $responseContent;
    }

    private function getServiceUrl(Address $address): string
    {
        $query = http_build_query([
                'address' => (string) $address,
                'sensor' => 'false'
            ]);

        return ($this->useHttps ? 'https' : 'http') . self::SERVICE_BASE_URL . '?' . $query;
    }

    public function getUseHttps(): bool
    {
        return $this->useHttps;
    }

    public function setUseHttps(bool $useHttps): self
    {
        $this->useHttps = $useHttps;

        return $this;
    }
}
