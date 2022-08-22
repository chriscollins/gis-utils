<?php

namespace ChrisCollins\GisUtils\Lookup;

use ChrisCollins\GisUtils\Coordinate\LatLong;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Address\AddressInterface;
use ChrisCollins\GeneralUtils\Json\JsonCodec;
use ChrisCollins\GeneralUtils\Curl\CurlHandle;
use ChrisCollins\GisUtils\Address\Address;
use ChrisCollins\GisUtils\Exception\GoogleGeocoderException;
use ChrisCollins\GisUtils\Exception\AddressNotFoundException;

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
    /**
     * @var string Constant for the datum that coordinates returned by the service use.
     */
    public const SERVICE_DATUM = DatumFactory::DATUM_WGS84;

    /**
     * @var string Constant for the base URL (without the scheme) for the service.
     */
    private const SERVICE_BASE_URL = 'maps.googleapis.com/maps/api/geocode/json';

    /**
     * @var DatumFactory The DatumFactory to use when creating LatLongs.
     */
    private DatumFactory $datumFactory;

    /**
     * @var CurlHandle The CurlHandle to use when making requests.
     */
    private CurlHandle $curlHandle;

    /**
     * @var JsonCodec The JsonCodec to use to decode JSON responses.
     */
    private JsonCodec $jsonCodec;

    /**
     * @var bool Whether or not HTTPS requests should be made.
     */
    private bool $useHttps = true;

    /**
     * Constructor.
     *
     * @param DatumFactory $datumFactory The DatumFactory.
     * @param CurlHandle $curlHandle The CurlHandle.
     * @param JsonCodec $jsonCodec The JsonCodec.
     */
    public function __construct(DatumFactory $datumFactory, CurlHandle $curlHandle, JsonCodec $jsonCodec)
    {
        $this->datumFactory = $datumFactory;
        $this->curlHandle = $curlHandle;
        $this->jsonCodec = $jsonCodec;
    }

    /**
     * Obtain a LatLng from an address.
     *
     * @param AddressInterface $address The address to lookup.
     *
     * @return LatLong The LatLong.
     *
     * @throws AddressNotFoundException If the address is not found.
     * @throws GoogleGeocoderException If there was an issue with the Google Geocoder service.
     * @throws JsonException If there was an issue with the format of the JSON.
     */
    public function addressToLatLong(AddressInterface $address): LatLong
    {
        $url = $this->getServiceUrl($address);
        $json = $this->makeRequest($url);

        return $this->parseServiceResponse($json);
    }

    /**
     * Parse the response from the service, creating a LatLong instance from it if possible.
     *
     * @param string $json The JSON from the response.
     *
     * @return LatLong A LatLong representing the response.
     *
     * @throws AddressNotFoundException If the address was not found.
     * @throws JsonException If the JSON was unable to be decoded.
     */
    private function parseServiceResponse(string $json): LatLong
    {
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

        if ($latLong === null) {
            throw new AddressNotFoundException('Unable to find coordinates for address.');
        }

        return $latLong;
    }

    /**
     * Make a request to the service.
     *
     * @param string $url The service URL.
     *
     * @return string The response JSON.
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

    /**
     * Get the URL for the service.
     *
     * @param Address $address The address.
     *
     * @return string The service URL.
     */
    private function getServiceUrl(Address $address): string
    {
        $query = http_build_query(
            [
                'address' => $address->toString(),
                'sensor' => 'false'
            ]
        );

        return ($this->useHttps ? 'https' : 'http') . self::SERVICE_BASE_URL . '?' . $query;
    }

    /**
     * Accessor method.
     *
     * @return bool The value of the property.
     */
    public function getUseHttps(): bool
    {
        return $this->useHttps;
    }

    /**
     * Mutator method.
     *
     * @param bool $useHttps The value of the property.
     *
     * @return static This object.
     */
    public function setUseHttps(bool $useHttps): self
    {
        $this->useHttps = $useHttps;

        return $this;
    }
}
