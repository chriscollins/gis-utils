<?php

namespace ChrisCollins\GisUtils\Address;

/**
 * AddressInterface
 *
 * An interface for addresses.
 */
interface AddressInterface
{
    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getHouseNumber();

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setHouseNumber($houseNumber);

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getHouseName();

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setHouseName($houseName);

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getAddress1();

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setAddress1($address1);

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getAddress2();

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setAddress2($address2);

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getTown();

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setTown($town);

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getCounty();

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setCounty($county);

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getCountry();

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setCountry($country);

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getPostcode();

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setPostcode($postcode);

    /**
     * Get a string representation of the object.
     *
     * @return string A string representation of the object.
     */
    public function toString();
}
