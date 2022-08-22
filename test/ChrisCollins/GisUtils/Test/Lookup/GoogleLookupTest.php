<?php

namespace ChrisCollins\GisUtils\Test\Lookup;

use ChrisCollins\GisUtils\Lookup\GoogleLookup;
use ChrisCollins\GisUtils\Address\Address;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GeneralUtils\Json\JsonCodec;
use ChrisCollins\GeneralUtils\Exception\JsonException;
use ChrisCollins\GisUtils\Exception\AddressNotFoundException;
use ChrisCollins\GisUtils\Exception\GoogleGeocoderException;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use ChrisCollins\GisUtils\Test\Fixture\GoogleGeocoderFixture;

/**
 * GoogleLookupTest
 */
class GoogleLookupTest extends AbstractTestCase
{
    /**
     * @var float Constant for latitude value for 10 Downing Street.
     */
    const DOWNING_STREET_LAT = 51.5033548;

    /**
     * @var float Constant for longitude value for 10 Downing Street.
     */
    const DOWNING_STREET_LONG = -0.1275644;

    /**
     * @var GoogleLookup A GoogleLookup instance.
     */
    private $instance;

    /**
     * @var GoogleGeocoderFixture A GoogleGeocoderFixture instance.
     */
    private $googleGeocoderFixture;

    /**
     * @var Address An Address instance.
     */
    private $address;

    /**
     * @var DatumFactory A DatumFactory instance.
     */
    private $datumFactory;

    /**
     * @var JsonCodec A JsonCodec instance.
     */
    private $jsonCodec;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        $this->googleGeocoderFixture = new GoogleGeocoderFixture();

        $this->datumFactory = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());

        $this->jsonCodec = new JsonCodec();

        $mockCurlHandle = $this->getMockCurlHandleForJson('successSingleAddress.json');

        $this->instance = new GoogleLookup($this->datumFactory, $mockCurlHandle, $this->jsonCodec);

        $this->address = new Address();
        $this->address->setHouseNumber(10)
            ->setAddress1('Downing Street')
            ->setTown('London')
            ->setCountry('England')
            ->setPostcode('SW1A 2AA');
    }

    /**
     * testGettersReturnValuesSetBySetters
     *
     * @param string $propertyName The name of the property.
     * @param mixed $propertyValue The value of the property.
     *
     * @dataProvider getPropertyNamesAndTestValues
     */
    public function testGettersReturnValuesSetBySetters($propertyName, $propertyValue): void
    {
        $ucfirstPropertyName = ucfirst($propertyName);

        $setter = 'set' . $ucfirstPropertyName;
        $getter = 'get' . $ucfirstPropertyName;

        // Assert setters return the object.
        $object = $this->instance->$setter($propertyValue);
        $this->assertInstanceOf('ChrisCollins\GisUtils\Lookup\GoogleLookup', $object);
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
        return array(
            array('useHttps', true)
        );
    }

    public function testSuccessfulSingleAddressResponseIsParsedCorrectly(): void
    {
        $latLong = $this->instance->addressToLatLong($this->address);

        $this->assertEquals(self::DOWNING_STREET_LAT, $latLong->getLatitude());
        $this->assertEquals(self::DOWNING_STREET_LONG, $latLong->getLongitude());
        $this->assertEquals(GoogleLookup::SERVICE_DATUM, $latLong->getDatum()->getName());
    }

    public function testFailJunkResponseThrowsExpectedException(): void
    {
        $exceptionThrown = false;

        $mockCurlHandle = $this->getMockCurlHandleForJson('failJunkResponse.json');
        $this->instance = new GoogleLookup($this->datumFactory, $mockCurlHandle, $this->jsonCodec);

        try {
            $latLong = $this->instance->addressToLatLong($this->address);
        } catch (JsonException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testFailNoLatLongThrowsExpectedException(): void
    {
        $exceptionThrown = false;

        $mockCurlHandle = $this->getMockCurlHandleForJson('failNoLatLong.json');
        $this->instance = new GoogleLookup($this->datumFactory, $mockCurlHandle, $this->jsonCodec);

        try {
            $latLong = $this->instance->addressToLatLong($this->address);
        } catch (AddressNotFoundException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testFailZeroResultsThrowsExpectedException(): void
    {
        $exceptionThrown = false;

        $mockCurlHandle = $this->getMockCurlHandleForJson('failZeroResults.json');
        $this->instance = new GoogleLookup($this->datumFactory, $mockCurlHandle, $this->jsonCodec);

        try {
            $latLong = $this->instance->addressToLatLong($this->address);
        } catch (AddressNotFoundException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testCurlErrorThrowsExpectedException(): void
    {
        $exceptionThrown = false;

        $mockCurlHandle = $this->getMockCurlHandleForJson('failJunkResponse.json', CURLE_COULDNT_RESOLVE_HOST);
        $this->instance = new GoogleLookup($this->datumFactory, $mockCurlHandle, $this->jsonCodec);

        try {
            $latLong = $this->instance->addressToLatLong($this->address);
        } catch (GoogleGeocoderException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
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
