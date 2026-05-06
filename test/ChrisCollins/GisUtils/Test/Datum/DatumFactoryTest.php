<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Datum;

use ChrisCollins\GisUtils\Datum\Datum;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;

/**
 * DatumFactoryTest
 */
final class DatumFactoryTest extends AbstractTestCase
{
    private DatumFactory $instance;

    protected function setUp(): void
    {
        $this->instance = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());
    }

    #[Test]
    public function createSetsExpectedPropertyValues(): void
    {
        $datum = $this->instance->create(DatumFactory::DATUM_WGS84);

        $this->assertInstanceOf(Datum::class, $datum);
        $this->assertSame(DatumFactory::DATUM_WGS84, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());


        $datum = $this->instance->create(DatumFactory::DATUM_OSGB36);

        $this->assertInstanceOf(Datum::class, $datum);
        $this->assertSame(DatumFactory::DATUM_OSGB36, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_AIRY_1830, $ellipsoid->getName());
    }

    #[Test]
    public function createDefaultCreatesWgs84Datum(): void
    {
        $datum = $this->instance->createDefault();

        $this->assertInstanceOf(Datum::class, $datum);
        $this->assertSame(DatumFactory::DATUM_WGS84, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
    }

    #[Test]
    public function creatingAnInvalidDatumThrowsAnException(): void
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
