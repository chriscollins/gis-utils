<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test;

use ChrisCollins\GisUtils\Address\Address;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * AddressTest
 */
final class AddressTest extends AbstractTestCase
{
    /** @var Address An Address instance. */
    private $instance;

    protected function setUp(): void
    {
        $this->instance = new Address();
    }

    /**
     * testGettersReturnValuesSetBySetters
     *
     * @param string $propertyName The name of the property.
     * @param mixed $propertyValue The value of the property.
     */
    #[DataProvider('getPropertyNamesAndTestValues')]
    #[Test]
    public function gettersReturnValuesSetBySetters($propertyName, $propertyValue): void
    {
        $ucfirstPropertyName = ucfirst((string) $propertyName);

        $setter = 'set' . $ucfirstPropertyName;
        $getter = 'get' . $ucfirstPropertyName;

        // Assert setters return the object.
        $object = $this->instance->$setter($propertyValue);
        $this->assertInstanceOf(Address::class, $object);
        $this->assertEquals($this->instance, $object);

        $this->assertEquals($propertyValue, $this->instance->$getter());
    }

    /**
     * Data provider to provide test values for each property of the object.
     *
     * @return Iterator<(int | string), mixed> An array, each element an array containing a property name and a test value.
     */
    public static function getPropertyNamesAndTestValues(): Iterator
    {
        yield ['houseNumber', '20'];
        yield ['houseName', 'Test House'];
        yield ['address1', 'Test Road'];
        yield ['address2', 'Test Area'];
        yield ['town', 'Test Town'];
        yield ['county', 'Test County'];
        yield ['country', 'Test Country'];
        yield ['postcode', 'AA11 1AA'];
    }

    #[Test]
    public function toStringMethodReturnsExpectedResult(): void
    {
        $address = $this->getTestAddress();

        $expected = "20 Test Road,\nTest Area,\nTest Town,\nTest County,\nTest Country,\nAA11 1AA";

        $this->assertEquals($expected, (string) $address);
    }

    #[Test]
    public function toStringMethodOverridesHouseNumberWithName(): void
    {
        $address = $this->getTestAddress();
        $address->setHouseName('Test House');

        $expected = "Test House,\nTest Road,\nTest Area,\nTest Town,\nTest County,\nTest Country,\nAA11 1AA";

        $this->assertEquals($expected, (string) $address);
    }

    /**
     * Get a test Address.
     */
    protected function getTestAddress(): Address
    {
        $address = new Address();
        $address->setHouseNumber('20')
            ->setAddress1('Test Road')
            ->setAddress2('Test Area')
            ->setTown('Test Town')
            ->setCounty('Test County')
            ->setCountry('Test Country')
            ->setPostcode('AA11 1AA');

        return $address;
    }
}
