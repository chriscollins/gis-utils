<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Coordinate;

use ChrisCollins\GisUtils\Coordinate\CartesianCoordinate;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * CartesianCoordinateTest
 */
final class CartesianCoordinateTest extends AbstractTestCase
{
    /** @var CartesianCoordinate A CartesianCoordinate instance. */
    private $instance;

    /** @var DatumFactory A DatumFactory instance. */
    private $datumFactory;

    protected function setUp(): void
    {
        $this->datumFactory = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());

        $this->instance = new CartesianCoordinate(123.4, 456.7, 789.1, $this->datumFactory->createDefault());
    }

    #[Test]
    public function constructorSetsExpectedPropertyValues(): void
    {
        $x = 123.4;
        $y = 456.7;
        $z = 789.1;
        $datum = $this->datumFactory->createDefault();

        $instance = new CartesianCoordinate($x, $y, $z, $datum);

        $this->assertSame($x, $instance->getX());
        $this->assertSame($y, $instance->getY());
        $this->assertSame($z, $instance->getZ());
        $this->assertEquals($datum, $instance->getDatum());
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
        $this->assertInstanceOf(CartesianCoordinate::class, $object);
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
        $datumFactory = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());
        yield ['x', 123.4];
        yield ['y', 456.7];
        yield ['z', 789.1];
        yield ['datum', $datumFactory->createDefault()];
    }

    #[Test]
    public function toLatLongReturnsExpectedResult(): void
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

    #[Test]
    public function toStringMethodReturnsExpectedResult(): void
    {
        $x = 123.4;
        $y = 456.7;
        $z = 789.1;

        $instance = new CartesianCoordinate($x, $y, $z, $this->datumFactory->createDefault());

        $expected = sprintf('%s, %s, %s', $x, $y, $z);

        $this->assertSame($expected, (string) $instance);
    }
}
