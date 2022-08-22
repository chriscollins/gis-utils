<?php

namespace ChrisCollins\GisUtils\Test\Ellipsoid;

use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;
use ChrisCollins\GisUtils\Test\AbstractTestCase;

/**
 * EllipsoidTest
 */
class EllipsoidTest extends AbstractTestCase
{
    /**
     * @var string Constant for name of WGS84 ellipsoid.
     */
    const WGS84_NAME = 'WGS84';

    /**
     * @var string Constant for equatorial radius in metres of WGS84 datum.
     */
    const WGS84_SEMI_MAJOR_AXIS_METRES = 6378137;

    /**
     * @var string Constant for polar radius in metres of WGS84 datum.
     */
    const WGS84_SEMI_MINOR_AXIS_METRES = 6356752.314140;

    /**
     * @var string Constant for flattening of WGS84 datum.
     */
    const WGS84_FLATTENING = 298.257223563;

    /**
     * @var Ellipsoid An Ellipsoid instance.
     */
    private $instance;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        $this->instance = new Ellipsoid(
            self::WGS84_NAME,
            self::WGS84_SEMI_MAJOR_AXIS_METRES,
            self::WGS84_SEMI_MINOR_AXIS_METRES,
            self::WGS84_FLATTENING
        );
    }

    public function testConstructorSetsExpectedPropertyValues(): void
    {
        $this->assertEquals(self::WGS84_NAME, $this->instance->getName());
        $this->assertEquals(self::WGS84_SEMI_MAJOR_AXIS_METRES, $this->instance->getSemiMajorAxisMetres());
        $this->assertEquals(self::WGS84_SEMI_MINOR_AXIS_METRES, $this->instance->getSemiMinorAxisMetres());
        $this->assertEquals(self::WGS84_FLATTENING, $this->instance->getFlattening());
    }

    /**
     * testGettersReturnValuesSetBySetters
     *
     * @param string $propertyName The name of the property.
     * @param mixed $propertyValue The value of the property.
     *
     * @dataProvider getPropertyNamesAndTestValues
     */
    public function testGettersReturnValuesSetBySetters($propertyName, $propertyValue): void
    {
        $ucfirstPropertyName = ucfirst($propertyName);

        $setter = 'set' . $ucfirstPropertyName;
        $getter = 'get' . $ucfirstPropertyName;

        // Assert setters return the object.
        $object = $this->instance->$setter($propertyValue);
        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $object);
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
            array('name', self::WGS84_NAME),
            array('semiMajorAxisMetres', self::WGS84_SEMI_MAJOR_AXIS_METRES),
            array('semiMinorAxisMetres', self::WGS84_SEMI_MINOR_AXIS_METRES),
            array('Flattening', self::WGS84_FLATTENING)
        );
    }

    public function testToStringMethodReturnsExpectedResult(): void
    {
        $this->assertEquals($this->instance->getName(), $this->instance->toString());
    }
}
