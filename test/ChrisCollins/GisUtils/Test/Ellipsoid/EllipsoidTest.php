<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Ellipsoid;

use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * EllipsoidTest
 */
final class EllipsoidTest extends AbstractTestCase
{
    /** @var string Constant for name of WGS84 ellipsoid. */
    public const WGS84_NAME = 'WGS84';

    /** @var int Constant for equatorial radius in metres of WGS84 datum. */
    public const WGS84_SEMI_MAJOR_AXIS_METRES = 6378137;

    /** @var float Constant for polar radius in metres of WGS84 datum. */
    public const WGS84_SEMI_MINOR_AXIS_METRES = 6356752.314140;

    /** @var float Constant for flattening of WGS84 datum. */
    public const WGS84_FLATTENING = 298.257223563;

    /** @var Ellipsoid An Ellipsoid instance. */
    private $instance;

    protected function setUp(): void
    {
        $this->instance = new Ellipsoid(
            self::WGS84_NAME,
            self::WGS84_SEMI_MAJOR_AXIS_METRES,
            self::WGS84_SEMI_MINOR_AXIS_METRES,
            self::WGS84_FLATTENING
        );
    }

    #[Test]
    public function constructorSetsExpectedPropertyValues(): void
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
        $this->assertInstanceOf(Ellipsoid::class, $object);
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
        yield ['name', self::WGS84_NAME];
        yield ['semiMajorAxisMetres', self::WGS84_SEMI_MAJOR_AXIS_METRES];
        yield ['semiMinorAxisMetres', self::WGS84_SEMI_MINOR_AXIS_METRES];
        yield ['Flattening', self::WGS84_FLATTENING];
    }

    #[Test]
    public function toStringMethodReturnsExpectedResult(): void
    {
        $this->assertEquals($this->instance->getName(), (string) $this->instance);
    }
}
