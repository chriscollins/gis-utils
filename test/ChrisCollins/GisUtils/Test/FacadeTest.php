<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test;

use ChrisCollins\GeneralUtils\Curl\CurlHandle;
use ChrisCollins\GeneralUtils\Json\JsonCodec;
use ChrisCollins\GisUtils\Address\Address;
use ChrisCollins\GisUtils\Datum\Datum;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Facade;
use ChrisCollins\GisUtils\Lookup\GoogleLookup;
use ChrisCollins\GisUtils\Test\Fixture\GoogleGeocoderFixture;
use ChrisCollins\GisUtils\Test\Fixture\LatLongsFixture;
use PHPUnit\Framework\Attributes\Test;

/**
 * FacadeTest
 */
final class FacadeTest extends AbstractTestCase
{
    /** @var Facade A Facade instance. */
    private $instance;

    /** @var Address An Address instance. */
    private $address;

    /** @var GoogleGeocoderFixture A GoogleGeocoderFixture instance. */
    private $googleGeocoderFixture;


    protected function setUp(): void
    {
        $this->googleGeocoderFixture = new GoogleGeocoderFixture();
        $latLongsFixture = new LatLongsFixture();

        $latLongsFixture->getLatLongPenYFan();

        $this->instance = new Facade();

        $this->address = new Address();
        $this->address->setHouseNumber('10')
            ->setAddress1('Downing Street')
            ->setTown('London')
            ->setCountry('England')
            ->setPostcode('SW1A 2AA');
    }

    #[Test]
    public function containerReturnsServicesThatAreOfTheExpectedClasses(): void
    {
        $this->assertInstanceOf(DatumFactory::class, $this->instance['DatumFactory']);

        $this->assertInstanceOf(HelmertTransformFactory::class, $this->instance['HelmertTransformFactory']);

        $this->assertInstanceOf(JsonCodec::class, $this->instance['JsonCodec']);

        $this->assertInstanceOf(GoogleLookup::class, $this->instance['GoogleLookup']);
    }

    #[Test]
    public function googleAddressToLatLongReturnsExpectedValue(): void
    {
        $mockCurlHandle = $this->getMockCurlHandleForJson('successSingleAddress.json');

        $this->instance['CurlHandle'] = (fn (): CurlHandle => $mockCurlHandle);

        $latLong = $this->instance->googleAddressToLatLong($this->address);

        $this->assertEqualsWithDelta(51.5033548, $latLong->getLatitude(), PHP_FLOAT_EPSILON);
        $this->assertEquals(-0.1275644, $latLong->getLongitude());
    }

    #[Test]
    public function createDatumSetsExpectedPropertyValues(): void
    {
        $datum = $this->instance->createDatum(DatumFactory::DATUM_WGS84);

        $this->assertInstanceOf(Datum::class, $datum);
        $this->assertSame(DatumFactory::DATUM_WGS84, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());


        $datum = $this->instance->createDatum(DatumFactory::DATUM_OSGB36);

        $this->assertInstanceOf(Datum::class, $datum);
        $this->assertSame(DatumFactory::DATUM_OSGB36, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_AIRY_1830, $ellipsoid->getName());
    }

    #[Test]
    public function createDefaultDatumCreatesWgs84Datum(): void
    {
        $datum = $this->instance->createDefaultDatum();

        $this->assertInstanceOf(Datum::class, $datum);
        $this->assertSame(DatumFactory::DATUM_WGS84, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
    }

    #[Test]
    public function createEllipsoidSetsExpectedPropertyValues(): void
    {
        $ellipsoid = $this->instance->createEllipsoid(EllipsoidFactory::ELLIPSOID_WGS84);

        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());

        $ellipsoid = $this->instance->createEllipsoid(EllipsoidFactory::ELLIPSOID_AIRY_1830);

        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_AIRY_1830, $ellipsoid->getName());
    }

    #[Test]
    public function createDefaultEllipsoidSetsExpectedPropertyValues(): void
    {
        $ellipsoid = $this->instance->createDefaultEllipsoid();

        $this->assertInstanceOf(Ellipsoid::class, $ellipsoid);
        $this->assertSame(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
    }

    /**
     * Get a CurlHandle that will return some mock JSON.
     *
     * @param string $jsonFileName The name of the JSON file to load.
     * @param int|null $errorCode An optional error code for the CurlHandle to return.
     *
     * @return CurlHandle A mock CurlHandle instance.
     */
    protected function getMockCurlHandleForJson(string $jsonFileName, ?int $errorCode = null): CurlHandle
    {
        $mockCurlHandle = $this->createStub(CurlHandle::class);

        $mockCurlHandle
            ->method('execute')
            ->willReturn($this->googleGeocoderFixture->getJsonFromFile($jsonFileName));

        $mockCurlHandle
            ->method('getErrorCode')
            ->willReturn($errorCode);

        return $mockCurlHandle;
    }
}
