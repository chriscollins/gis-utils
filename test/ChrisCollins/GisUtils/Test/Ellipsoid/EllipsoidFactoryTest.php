<?php

namespace ChrisCollins\GisUtils\Test\Ellipsoid;

use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Datum\Datum;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use \InvalidArgumentException;

/**
 * EllipsoidFactoryTest
 */
class EllipsoidFactoryTest extends AbstractTestCase
{
    /**
     * @var string Constant for equatorial radius in metres of WGS84 ellipsoid.
     */
    const WGS84_EQUATORIAL_RADIUS_METRES = 6378137;

    /**
     * @var string Constant for polar radius in metres of WGS84 ellipsoid.
     */
    const WGS84_POLAR_RADIUS_METRES = 6356752.314140;

    /**
     * @var string Constant for flattening of WGS84 ellipsoid.
     */
    const WGS84_FLATTENING = 298.257223563;

    /**
     * @var string Constant for equatorial radius in metres of AIRY_1830 ellipsoid.
     */
    const AIRY_1830_EQUATORIAL_RADIUS_METRES = 6377563.396;

    /**
     * @var string Constant for polar radius in metres of AIRY_1830 ellipsoid.
     */
    const AIRY_1830_POLAR_RADIUS_METRES = 6356256.910;

    /**
     * @var string Constant for flattening of AIRY_1830 ellipsoid.
     */
    const AIRY_1830_FLATTENING = 299.3249646;

    /**
     * @var EllipsoidFactory An EllipsoidFactory instance.
     */
    private $instance;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        $this->instance = new EllipsoidFactory();
    }

    public function testCreateSetsExpectedPropertyValues()
    {
        $ellipsoid = $this->instance->create(EllipsoidFactory::ELLIPSOID_WGS84);

        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
        $this->assertEquals(self::WGS84_EQUATORIAL_RADIUS_METRES, $ellipsoid->getSemiMajorAxisMetres());
        $this->assertEquals(self::WGS84_POLAR_RADIUS_METRES, $ellipsoid->getSemiMinorAxisMetres());
        $this->assertEquals(self::WGS84_FLATTENING, $ellipsoid->getFlattening());

        $ellipsoid = $this->instance->create(EllipsoidFactory::ELLIPSOID_AIRY_1830);

        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_AIRY_1830, $ellipsoid->getName());
        $this->assertEquals(self::AIRY_1830_EQUATORIAL_RADIUS_METRES, $ellipsoid->getSemiMajorAxisMetres());
        $this->assertEquals(self::AIRY_1830_POLAR_RADIUS_METRES, $ellipsoid->getSemiMinorAxisMetres());
        $this->assertEquals(self::AIRY_1830_FLATTENING, $ellipsoid->getFlattening());
    }

    public function testCreateDefaultSetsExpectedPropertyValues()
    {
        $ellipsoid = $this->instance->createDefault();

        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
        $this->assertEquals(self::WGS84_EQUATORIAL_RADIUS_METRES, $ellipsoid->getSemiMajorAxisMetres());
        $this->assertEquals(self::WGS84_POLAR_RADIUS_METRES, $ellipsoid->getSemiMinorAxisMetres());
        $this->assertEquals(self::WGS84_FLATTENING, $ellipsoid->getFlattening());
    }

    public function testCreatingAnInvalidEllipsoidThrowsAnException()
    {
        $exceptionThrown = false;

        try {
            $this->instance->create('Nonexistant');
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }
}
