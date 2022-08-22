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
     * @var string|null The house number.
     */
    private ?string $houseNumber = null;

    /**
     * @var string|null The house name.
     */
    private ?string $houseName = null;

    /**
     * @var string|null The first line of the address.
     */
    private ?string $address1 = null;

    /**
     * @var string|null The second line of the address.
     */
    private ?string $address2 = null;

    /**
     * @var string|null The town.
     */
    private ?string $town = null;

    /**
     * @var string|null The county.
     */
    private ?string $county = null;

    /**
     * @var string|null The country.
     */
    private ?string $country = null;

    /**
     * @var string|null The postcode.
     */
    private ?string $postcode = null;

    /**
     * {@inheritDoc}
     */
    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    /**
     * {@inheritDoc}
     */
    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHouseName(): string
    {
        return $this->houseName;
    }

    /**
     * {@inheritDoc}
     */
    public function setHouseName(?string $houseName): self
    {
        $this->houseName = $houseName;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress1(): string
    {
        return $this->address1;
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress1(?string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress2(): string
    {
        return $this->address2;
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTown(): string
    {
        return $this->town;
    }

    /**
     * {@inheritDoc}
     */
    public function setTown(?string $town): self
    {
        $this->town = $town;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCounty(): string
    {
        return $this->county;
    }

    /**
     * {@inheritDoc}
     */
    public function setCounty(?string $county): self
    {
        $this->county = $county;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * {@inheritDoc}
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * {@inheritDoc}
     */
    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get a string representation of the object.
     *
     * @return string A string representation of the object.
     */
    public function toString(): string
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
