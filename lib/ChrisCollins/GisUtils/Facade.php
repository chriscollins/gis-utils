<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils;

use ChrisCollins\GeneralUtils\Curl\CurlHandle;
use ChrisCollins\GeneralUtils\Exception\JsonException;
use ChrisCollins\GeneralUtils\Json\JsonCodec;
use ChrisCollins\GisUtils\Address\Address;
use ChrisCollins\GisUtils\Coordinate\LatLong;
use ChrisCollins\GisUtils\Datum\Datum;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Exception\AddressNotFoundException;
use ChrisCollins\GisUtils\Exception\GoogleGeocoderException;
use ChrisCollins\GisUtils\Lookup\GoogleLookup;
use InvalidArgumentException;
use Pimple\Container;

/**
 * Facade
 *
 * A facade for the library.
 */
class Facade extends Container
{
    public function __construct()
    {
        $this['EllipsoidFactory'] = (fn (): EllipsoidFactory => new EllipsoidFactory());

        $this['HelmertTransformFactory'] = (fn (): HelmertTransformFactory => new HelmertTransformFactory());

        $this['DatumFactory'] = (
            fn ($container): DatumFactory => new DatumFactory(
                $container['EllipsoidFactory'],
                $container['HelmertTransformFactory']
            )
        );

        $this['CurlHandle'] = (fn (): CurlHandle => new CurlHandle());

        $this['JsonCodec'] = (fn (): JsonCodec => new JsonCodec());

        $this['GoogleLookup'] = (
            fn ($container): GoogleLookup => new GoogleLookup(
                $container['DatumFactory'],
                $container['CurlHandle'],
                $container['JsonCodec']
            )
        );
    }

    /**
     * Convert an address to a LatLong via Google's geocoder.
     *
     * @throws AddressNotFoundException If the address is not found.
     * @throws GoogleGeocoderException If there was an issue with the Google Geocoder service.
     * @throws JsonException If there was an issue with the format of the JSON.
     */
    public function googleAddressToLatLong(Address $address): LatLong
    {
        return $this['GoogleLookup']->addressToLatLong($address);
    }

    /**
     * Create a Datum via the factory.
     *
     * @throws InvalidArgumentException If the datum is not supported.
     */
    public function createDatum(string $name): Datum
    {
        return $this['DatumFactory']->create($name);
    }

    /**
     * Create the default Datum via the factory.
     */
    public function createDefaultDatum(): Datum
    {
        return $this['DatumFactory']->createDefault();
    }

    /**
     * Create an Ellipsoid via the factory.
     *
     * @throws InvalidArgumentException If the ellipsoid is not supported.
     */
    public function createEllipsoid(string $name): Ellipsoid
    {
        return $this['EllipsoidFactory']->create($name);
    }

    /**
     * Create the default Ellipsoid via the factory.
     */
    public function createDefaultEllipsoid(): Ellipsoid
    {
        return $this['EllipsoidFactory']->createDefault();
    }
}
