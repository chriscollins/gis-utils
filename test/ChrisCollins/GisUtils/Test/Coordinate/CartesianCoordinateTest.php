<?php

namespace ChrisCollins\GisUtils\Test\Coordinate;

use ChrisCollins\GisUtils\Test\AbstractTestCase;
use ChrisCollins\GisUtils\Coordinate\CartesianCoordinate;
use ChrisCollins\GisUtils\Coordinate\LatLong;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;

/**
 * CartesianCoordinateTest
 */
class CartesianCoordinateTest extends AbstractTestCase
{
    /**
     * @var CartesianCoordinate A CartesianCoordinate instance.
     */
    protected $instance = null;

    /**
     * @var DatumFactory A DatumFactory instance.
     */
    protected $datumFactory = null;

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->datumFactory = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());

        $this->instance = new CartesianCoordinate(123.4, 456.7, 789.1, $this->datumFactory->createDefault());
    }

    public function testConstructorSetsExpectedPropertyValues()
    {
        $x = 123.4;
        $y = 456.7;
        $z = 789.1;
        $datum = $this->datumFactory->createDefault();

        $instance = new CartesianCoordinate($x, $y, $z, $datum);

        $this->assertEquals($x, $instance->getX());
        $this->assertEquals($y, $instance->getY());
        $this->assertEquals($z, $instance->getZ());
        $this->assertEquals($datum, $instance->getDatum());
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
        $this->assertInstanceOf('ChrisCollins\GisUtils\Coordinate\CartesianCoordinate', $object);
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
        $datumFactory = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());

        return array(
            array('x', 123.4),
            array('y', 456.7),
            array('z', 789.1),
            array('datum', $datumFactory->createDefault())
        );
    }

    public function testToLatLongReturnsExpectedResult()
    {
        $datum = $this->datumFactory->create(DatumFactory::DATUM_OSGB36);
        $this->instance = new CartesianCoordinate(3874938.8795, 116218.5175, 5047168.1878, $datum);

        $latLong = $this->instance->toLatLong();

        $significantFigures = 5;

        $this->assertEqualsWhenRounded(52.65757, $latLong->getLatitude(), $significantFigures);
        $this->assertEqualsWhenRounded(1.71792, $latLong->getLongitude(), $significantFigures);
        $this->assertEqualsWhenRounded(24.7, $latLong->getHeight(), $significantFigures);
        $this->assertEquals($datum, $latLong->getDatum());
    }

    public function testToStringMethodReturnsExpectedResult()
    {
        $x = 123.4;
        $y = 456.7;
        $z = 789.1;

        $instance = new CartesianCoordinate($x, $y, $z, $this->datumFactory->createDefault());

        $expected = "{$x}, {$y}, {$z}";

        $this->assertEquals($expected, $instance->toString());
    }
}
