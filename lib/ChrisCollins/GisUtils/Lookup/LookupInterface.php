<?php

namespace ChrisCollins\GisUtils\Lookup;

use ChrisCollins\GisUtils\Address\AddressInterface;
use ChrisCollins\GisUtils\Coordinate\LatLong;

/**
 * LookupInterface
 *
 * An interface for finding out data about an address..
 */
interface LookupInterface
{
    /**
     * Obtain a LatLng from an address.
     *
     * @param AddressInterface $address The address to lookup.
     *
     * @return LatLong The LatLong.
     *
     * @throws AddressNotFoundException If the address is not found.
     */
    public function addressToLatLong(AddressInterface $address): LatLong;
}
