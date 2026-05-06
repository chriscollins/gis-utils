<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Ellipsoid;

use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;

/**
 * EllipsoidFactoryTest
 */
final class EllipsoidFactoryTest extends AbstractTestCase
{
    /** @var int Constant for equatorial radius in metres of WGS84 ellipsoid. */
    public const WGS84_EQUATORIAL_RADIUS_METRES = 6378137;

    /** @var float Constant for polar radius in metres of WGS84 ellipsoid. */
    public const WGS84_POLAR_RADIUS_METRES = 6356752.314140;

    /** @var float Constant for flattening of WGS84 ellipsoid. */
    public const WGS84_FLATTENING = 298.257223563;

    /** @var float Constant for equatorial radius in metres of AIRY_1830 ellipsoid. */
    public const AIRY_1830_EQUATORIAL_RADIUS_METRES = 6377563.396;

    /** @var float Constant for polar radius in metres of AIRY_1830 ellipsoid. */
    public const AIRY_1830_POLAR_RADIUS_METRES = 6356256.910;

    /** @var float Constant for flattening of AIRY_1830 ellipsoid. */
    public const AIRY_1830_FLATTENING = 299.3249646;

    /** @var EllipsoidFactory An EllipsoidFactory instance. */
    private $instance;

    protected function setUp(): void
    {
        $this->instance = new EllipsoidFactory();
    }

    #[Test]
    public function createSetsExpectedPropertyValues(): void
    {
        $ellipsoid = $this->instance->create(EllipsoidFactory::ELLIPSOID_WGS84);

        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
        $this->assertEquals(self::WGS84_EQUATORIAL_RADIUS_METRES, $ellipsoid->getSemiMajorAxisMetres());
        $this->assertSame(self::WGS84_POLAR_RADIUS_METRES, $ellipsoid->getSemiMinorAxisMetres());
        $this->assertSame(self::WGS84_FLATTENING, $ellipsoid->getFlattening());

        $ellipsoid = $this->instance->create(EllipsoidFactory::ELLIPSOID_AIRY_1830);

        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_AIRY_1830, $ellipsoid->getName());
        $this->assertSame(self::AIRY_1830_EQUATORIAL_RADIUS_METRES, $ellipsoid->getSemiMajorAxisMetres());
        $this->assertSame(self::AIRY_1830_POLAR_RADIUS_METRES, $ellipsoid->getSemiMinorAxisMetres());
        $this->assertSame(self::AIRY_1830_FLATTENING, $ellipsoid->getFlattening());
    }

    #[Test]
    public function createDefaultSetsExpectedPropertyValues(): void
    {
        $ellipsoid = $this->instance->createDefault();

        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
        $this->assertEquals(self::WGS84_EQUATORIAL_RADIUS_METRES, $ellipsoid->getSemiMajorAxisMetres());
        $this->assertSame(self::WGS84_POLAR_RADIUS_METRES, $ellipsoid->getSemiMinorAxisMetres());
        $this->assertSame(self::WGS84_FLATTENING, $ellipsoid->getFlattening());
    }

    #[Test]
    public function creatingAnInvalidEllipsoidThrowsAnException(): void
    {
        $exceptionThrown = false;

        try {
            $this->instance->create('Nonexistant');
        } catch (InvalidArgumentException) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }
}
