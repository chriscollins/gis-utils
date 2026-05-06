<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Address;

use Stringable;

/**
 * Address
 *
 * A class to represent an address.
 */
class Address implements Stringable
{
    private ?string $houseNumber = null;

    private ?string $houseName = null;

    private ?string $address1 = null;

    private ?string $address2 = null;

    private ?string $town = null;

    private ?string $county = null;

    private ?string $country = null;

    private ?string $postcode = null;

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getHouseName(): string
    {
        return $this->houseName;
    }

    public function setHouseName(?string $houseName): self
    {
        $this->houseName = $houseName;

        return $this;
    }

    public function getAddress1(): string
    {
        return $this->address1;
    }

    public function setAddress1(?string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getTown(): string
    {
        return $this->town;
    }

    public function setTown(?string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getCounty(): string
    {
        return $this->county;
    }

    public function setCounty(?string $county): self
    {
        $this->county = $county;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function __toString(): string
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

        return $string . implode(",\n", $usedFields);
    }
}
