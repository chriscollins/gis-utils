<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Datum;

use ChrisCollins\GisUtils\Datum\Datum;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * DatumTest
 */
final class DatumTest extends AbstractTestCase
{
    /** @var string Constant for name of WGS84 datum. */
    public const WGS84_NAME = 'WGS84';

    /** @var int Constant for equatorial radius in metres of WGS84 datum. */
    public const WGS84_SEMI_MAJOR_AXIS_METRES = 6378137;

    /** @var float Constant for polar radius in metres of WGS84 datum. */
    public const WGS84_SEMI_MINOR_AXIS_METRES = 6356752.314140;

    /** @var float Constant for inverse flattening of WGS84 datum. */
    public const WGS84_INVERSE_FLATTENING = 298.257223563;

    /** @var Datum A Datum instance. */
    private $instance;

    /** @var Ellipsoid The ellipsoid to use. */
    private $ellipsoid;

    protected function setUp(): void
    {
        $ellipsoidFactory = new EllipsoidFactory();
        $this->ellipsoid = $ellipsoidFactory->create(EllipsoidFactory::ELLIPSOID_WGS84);

        $this->instance = new Datum(self::WGS84_NAME, $this->ellipsoid);
    }

    #[Test]
    public function constructorSetsExpectedPropertyValues(): void
    {
        $this->assertEquals(self::WGS84_NAME, $this->instance->getName());
        $this->assertEquals($this->ellipsoid, $this->instance->getEllipsoid());
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
        $this->assertInstanceOf(Datum::class, $object);
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
        $ellipsoidFactory = new EllipsoidFactory();
        $ellipsoid = $ellipsoidFactory->create(EllipsoidFactory::ELLIPSOID_WGS84);
        $helmertTransformFactory = new HelmertTransformFactory();
        $helmertTransform = $helmertTransformFactory->createTransformFromBaseToDatum(DatumFactory::DATUM_OSGB36);
        yield ['name', self::WGS84_NAME];
        yield ['ellipsoid', $ellipsoid];
        yield ['fromWgs84HelmertTransform', $helmertTransform];
    }

    #[Test]
    public function getToWgs84HelmertTransformReturnsReversedHelmertTransform(): void
    {
        $helmertTransformFactory = new HelmertTransformFactory();
        $helmertTransform = $helmertTransformFactory->createTransformFromBaseToDatum(DatumFactory::DATUM_OSGB36);

        $this->instance->setFromWgs84HelmertTransform($helmertTransform);

        $this->assertEquals(
            $helmertTransform->getReverseHelmertTransform(),
            $this->instance->getToWgs84HelmertTransform()
        );
    }

    #[Test]
    public function toStringMethodReturnsExpectedResult(): void
    {
        $this->assertEquals($this->instance->getName(), (string) $this->instance);
    }
}
