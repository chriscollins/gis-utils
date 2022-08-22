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
     * @return string|null The value of the property.
     */
    public function getHouseNumber(): ?string;

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return static This object.
     */
    public function setHouseNumber(?string $houseNumber): AddressInterface;

    /**
     * Accessor method.
     *
     * @return string|null The value of the property.
     */
    public function getHouseName(): ?string;

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return static This object.
     */
    public function setHouseName(?string $houseName): AddressInterface;

    /**
     * Accessor method.
     *
     * @return string|null The value of the property.
     */
    public function getAddress1(): ?string;

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return static This object.
     */
    public function setAddress1(?string $address1): AddressInterface;

    /**
     * Accessor method.
     *
     * @return string|null The value of the property.
     */
    public function getAddress2(): ?string;

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return static This object.
     */
    public function setAddress2(?string $address2): AddressInterface;

    /**
     * Accessor method.
     *
     * @return string|null The value of the property.
     */
    public function getTown(): ?string;

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return static This object.
     */
    public function setTown(?string $town): AddressInterface;

    /**
     * Accessor method.
     *
     * @return string|null The value of the property.
     */
    public function getCounty(): ?string;

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return static This object.
     */
    public function setCounty(?string $county): AddressInterface;

    /**
     * Accessor method.
     *
     * @return string|null The value of the property.
     */
    public function getCountry(): ?string;

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return static This object.
     */
    public function setCountry(?string $country): AddressInterface;

    /**
     * Accessor method.
     *
     * @return string|null The value of the property.
     */
    public function getPostcode(): ?string;

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return static This object.
     */
    public function setPostcode(?string $postcode): AddressInterface;

    /**
     * Get a string representation of the object.
     *
     * @return string|null A string representation of the object.
     */
    public function toString(): ?string;
}
