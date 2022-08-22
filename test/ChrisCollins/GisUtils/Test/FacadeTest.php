<?php

namespace ChrisCollins\GisUtils\Test;

use ChrisCollins\GisUtils\Facade;
use ChrisCollins\GisUtils\Address\Address;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Test\Fixture\GoogleGeocoderFixture;
use ChrisCollins\GisUtils\Test\Fixture\LatLongsFixture;

/**
 * FacadeTest
 */
class FacadeTest extends AbstractTestCase
{
    /**
     * @var Facade A Facade instance.
     */
    private $instance;

    /**
     * @var Address An Address instance.
     */
    private $address;

    /**
     * @var GoogleGeocoderFixture A GoogleGeocoderFixture instance.
     */
    private $googleGeocoderFixture;

    /**
     * @var LatLongsFixture A LatLongsFixture instance.
     */
    private $latLongsFixture;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        $this->googleGeocoderFixture = new GoogleGeocoderFixture();
        $this->latLongsFixture = new LatLongsFixture();

        $this->latLong = $this->latLongsFixture->getLatLongPenYFan();

        $this->instance = new Facade();

        $this->address = new Address();
        $this->address->setHouseNumber(10)
            ->setAddress1('Downing Street')
            ->setTown('London')
            ->setCountry('England')
            ->setPostcode('SW1A 2AA');
    }

    public function testContainerReturnsServicesThatAreOfTheExpectedClasses(): void
    {
        $this->assertInstanceOf(
            'ChrisCollins\GisUtils\Datum\DatumFactory',
            $this->instance['DatumFactory']
        );

        $this->assertInstanceOf(
            'ChrisCollins\GisUtils\Equation\HelmertTransformFactory',
            $this->instance['HelmertTransformFactory']
        );

        $this->assertInstanceOf(
            'ChrisCollins\GeneralUtils\Json\JsonCodec',
            $this->instance['JsonCodec']
        );

        $this->assertInstanceOf(
            'ChrisCollins\GisUtils\Lookup\GoogleLookup',
            $this->instance['GoogleLookup']
        );
    }

    public function testGoogleAddressToLatLongReturnsExpectedValue(): void
    {
        $mockCurlHandle = $this->getMockCurlHandleForJson('successSingleAddress.json');

        $this->instance['CurlHandle'] = function () use ($mockCurlHandle) {
            return $mockCurlHandle;
        };

        $latLong = $this->instance->googleAddressToLatLong($this->address);

        $this->assertEquals(51.5033548, $latLong->getLatitude());
        $this->assertEquals(-0.1275644, $latLong->getLongitude());
    }

    public function testCreateDatumSetsExpectedPropertyValues(): void
    {
        $datum = $this->instance->createDatum(DatumFactory::DATUM_WGS84);

        $this->assertInstanceOf('ChrisCollins\GisUtils\Datum\Datum', $datum);
        $this->assertEquals(DatumFactory::DATUM_WGS84, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());


        $datum = $this->instance->createDatum(DatumFactory::DATUM_OSGB36);

        $this->assertInstanceOf('ChrisCollins\GisUtils\Datum\Datum', $datum);
        $this->assertEquals(DatumFactory::DATUM_OSGB36, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_AIRY_1830, $ellipsoid->getName());
    }

    public function testCreateDefaultDatumCreatesWgs84Datum(): void
    {
        $datum = $this->instance->createDefaultDatum();

        $this->assertInstanceOf('ChrisCollins\GisUtils\Datum\Datum', $datum);
        $this->assertEquals(DatumFactory::DATUM_WGS84, $datum->getName());

        $ellipsoid = $datum->getEllipsoid();
        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
    }

    public function testCreateEllipsoidSetsExpectedPropertyValues(): void
    {
        $ellipsoid = $this->instance->createEllipsoid(EllipsoidFactory::ELLIPSOID_WGS84);

        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());

        $ellipsoid = $this->instance->createEllipsoid(EllipsoidFactory::ELLIPSOID_AIRY_1830);

        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_AIRY_1830, $ellipsoid->getName());
    }

    public function testCreateDefaultEllipsoidSetsExpectedPropertyValues(): void
    {
        $ellipsoid = $this->instance->createDefaultEllipsoid();

        $this->assertInstanceOf('ChrisCollins\GisUtils\Ellipsoid\Ellipsoid', $ellipsoid);
        $this->assertEquals(EllipsoidFactory::ELLIPSOID_WGS84, $ellipsoid->getName());
    }

    /**
     * Get a CurlHandle that will return some mock JSON.
     *
     * @param string $jsonFileName The name of the JSON file to load.
     * @param int|null $errorCode An optional error code for the CurlHandle to return.
     *
     * @return CurlHandle A mock CurlHandle instance.
     */
    protected function getMockCurlHandleForJson($jsonFileName, $errorCode = null)
    {
        $mockCurlHandle = $this->createMock(
            'ChrisCollins\GeneralUtils\Curl\CurlHandle',
            array('execute', 'getErrorCode')
        );

        $mockCurlHandle->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($this->googleGeocoderFixture->getJsonFromFile($jsonFileName)));

        $mockCurlHandle->expects($this->any())
            ->method('getErrorCode')
            ->will($this->returnValue($errorCode));

        return $mockCurlHandle;
    }
}
