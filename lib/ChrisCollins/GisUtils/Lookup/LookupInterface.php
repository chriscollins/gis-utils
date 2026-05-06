<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Lookup;

use ChrisCollins\GisUtils\Address\Address;
use ChrisCollins\GisUtils\Coordinate\LatLong;
use ChrisCollins\GisUtils\Exception\AddressNotFoundException;

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
     * @throws AddressNotFoundException If the address is not found.
     */
    public function addressToLatLong(Address $address): LatLong;
}
