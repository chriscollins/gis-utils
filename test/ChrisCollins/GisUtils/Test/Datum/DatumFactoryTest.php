<?php

namespace ChrisCollins\GisUtils\Test\Datum;

use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Datum\Datum;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use \InvalidArgumentException;

/**
 * DatumFactoryTest
 */
class DatumFactoryTest extends AbstractTestCase
{
    /**
     * @var Datum A Datum instance.
     */
    private $instance;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        $this->instance = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());
    }

    public function testCreateSetsExpectedPropertyValues()
    {
        $datum = $this->instance->create(DatumFactory::DATUM_WGS84);

        $this->assertInstanceOf('ChrisCollins\GisUtils\Datum\Datum', $datum);
        $this->assertEquals(DatumFactory::DATUM_WGS84, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());


        $datum = $this->instance->create(DatumFactory::DATUM_OSGB36);

        $this->assertInstanceOf('ChrisCollins\GisUtils\Datum\Datum', $datum);
        $this->assertEquals(DatumFactory::DATUM_OSGB36, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_AIRY_1830, $ellipsoid->getName());
    }

    public function testCreateDefaultCreatesWgs84Datum()
    {
        $datum = $this->instance->createDefault();

        $this->assertInstanceOf('ChrisCollins\GisUtils\Datum\Datum', $datum);
        $this->assertEquals(DatumFactory::DATUM_WGS84, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
    }

    public function testCreatingAnInvalidDatumThrowsAnException()
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
