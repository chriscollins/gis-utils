<?php

namespace ChrisCollins\GisUtils;

use ChrisCollins\GeneralUtils\Curl\CurlHandle;
use ChrisCollins\GeneralUtils\Json\JsonCodec;
use ChrisCollins\GisUtils\Address\AddressInterface;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Lookup\GoogleLookup;
use Pimple\Container;

/**
 * Facade
 *
 * A facade for the library.
 */
class Facade extends Container
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this['EllipsoidFactory'] = function () {
            return new EllipsoidFactory();
        };

        $this['HelmertTransformFactory'] = function () {
            return new HelmertTransformFactory();
        };

        $this['DatumFactory'] = function ($container) {
            return new DatumFactory($container['EllipsoidFactory'], $container['HelmertTransformFactory']);
        };

        $this['CurlHandle'] = function () {
            return new CurlHandle();
        };

        $this['JsonCodec'] = function () {
            return new JsonCodec();
        };

        $this['GoogleLookup'] = function ($container) {
            return new GoogleLookup($container['DatumFactory'], $container['CurlHandle'], $container['JsonCodec']);
        };
    }

    /**
     * Convert an address to a LatLong via Google's geocoder.
     *
     * @param AddressInterface $address The address to look up.
     *
     * @return LatLong A LatLong representing the address.
     *
     * @throws AddressNotFoundException If the address is not found.
     * @throws GoogleGeocoderException If there was an issue with the Google Geocoder service.
     * @throws JsonException If there was an issue with the format of the JSON.
     */
    public function googleAddressToLatLong(AddressInterface $address)
    {
        return $this['GoogleLookup']->addressToLatLong($address);
    }

    /**
     * Create a Datum via the factory.
     *
     * @param string $name The name of the datum.
     *
     * @return Datum A Datum.
     * @throws InvalidArgumentException If the datum is not supported.
     */
    public function createDatum($name)
    {
        return $this['DatumFactory']->create($name);
    }

    /**
     * Create the default Datum via the factory.
     *
     * @return Datum A Datum.
     */
    public function createDefaultDatum()
    {
        return $this['DatumFactory']->createDefault();
    }

    /**
     * Create an Ellipsoid via the factory.
     *
     * @param string $name The name of the ellipsoid.
     *
     * @return Ellipsoid An Ellipsoid.
     * @throws InvalidArgumentException If the ellipsoid is not supported.
     */
    public function createEllipsoid($name)
    {
        return $this['EllipsoidFactory']->create($name);
    }

    /**
     * Create the default Ellipsoid via the factory.
     *
     * @return Ellipsoid An Ellipsoid.
     */
    public function createDefaultEllipsoid()
    {
        return $this['EllipsoidFactory']->createDefault();
    }
}
