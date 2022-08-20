<?php

namespace ChrisCollins\GisUtils\Address;

/**
 * Address
 *
 * A class to represent an address.
 */
class Address implements AddressInterface
{
    /**
     * @var string The house number.
     */
    private $houseNumber;

    /**
     * @var string The house name.
     */
    private $houseName;

    /**
     * @var string The first line of the address.
     */
    private $address1;

    /**
     * @var string The second line of the address.
     */
    private $address2;

    /**
     * @var string The town.
     */
    private $town;

    /**
     * @var string The county.
     */
    private $county;

    /**
     * @var string The country.
     */
    private $country;

    /**
     * @var string The postcode.
     */
    private $postcode;

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getHouseName()
    {
        return $this->houseName;
    }

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setHouseName($houseName)
    {
        $this->houseName = $houseName;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setCounty($county)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Mutator method.
     *
     * @param string $houseNumber The new value of the property.
     *
     * @return Address This object.
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get a string representation of the object.
     *
     * @return string A string representation of the object.
     */
    public function toString()
    {
        $string = '';

        if ($this->houseName !== null && strlen($this->houseName)) {
            $string .= $this->houseName . ",\n";
        } elseif ($this->houseNumber !== null && strlen($this->houseNumber)) {
            $string .= $this->houseNumber . ' ';
        }

        $fieldList = [
            $this->address1,
            $this->address2,
            $this->town,
            $this->county,
            $this->country,
            $this->postcode
        ];

        $usedFields = [];

        foreach ($fieldList as $field) {
            if ($field !== null && strlen($field)) {
                $usedFields[] = $field;
            }
        }

        $string .= implode(",\n", $usedFields);

        return $string;
    }
}
