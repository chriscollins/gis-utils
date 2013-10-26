<?php

namespace ChrisCollins\GisUtils\Test;

use ChrisCollins\GisUtils\Address\Address;
use ChrisCollins\GisUtils\Test\AbstractTestCase;

/**
 * AddressTest
 */
class AddressTest extends AbstractTestCase
{
    /**
     * @var Address An Address instance.
     */
    protected $instance = null;

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->instance = new Address();
    }

    /**
     * testGettersReturnValuesSetBySetters
     *
     * @param string $propertyName The name of the property.
     * @param mixed $propertyValue The value of the property.
     *
     * @dataProvider getPropertyNamesAndTestValues
     */
    public function testGettersReturnValuesSetBySetters($propertyName, $propertyValue)
    {
        $ucfirstPropertyName = ucfirst($propertyName);

        $setter = 'set' . $ucfirstPropertyName;
        $getter = 'get' . $ucfirstPropertyName;

        // Assert setters return the object.
        $object = $this->instance->$setter($propertyValue);
        $this->assertInstanceOf('ChrisCollins\GisUtils\Address\Address', $object);
        $this->assertEquals($this->instance, $object);

        $this->assertEquals($propertyValue, $this->instance->$getter());
    }

    /**
     * Data provider to provide test values for each property of the object.
     *
     * @return array An array, each element an array containing a property name and a test value.
     */
    public static function getPropertyNamesAndTestValues()
    {
        return array(
            array('houseNumber', '20'),
            array('houseName', 'Test House'),
            array('address1', 'Test Road'),
            array('address2', 'Test Area'),
            array('town', 'Test Town'),
            array('county', 'Test County'),
            array('country', 'Test Country'),
            array('postcode', 'AA11 1AA')
        );
    }

    public function testToStringMethodReturnsExpectedResult()
    {
        $address = $this->getTestAddress();

        $expected = "20 Test Road,\nTest Area,\nTest Town,\nTest County,\nTest Country,\nAA11 1AA";

        $this->assertEquals($expected, $address->toString());
    }

    public function testToStringMethodOverridesHouseNumberWithName()
    {
        $address = $this->getTestAddress();
        $address->setHouseName('Test House');

        $expected = "Test House,\nTest Road,\nTest Area,\nTest Town,\nTest County,\nTest Country,\nAA11 1AA";

        $this->assertEquals($expected, $address->toString());
    }

    /**
     * Get a test Address.
     *
     * @return Address The Address.
     */
    protected function getTestAddress()
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
