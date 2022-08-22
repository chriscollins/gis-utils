<?php

namespace ChrisCollins\GisUtils\Test\Coordinate;

use ChrisCollins\GisUtils\Coordinate\LatLong;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Test\Fixture\LatLongsFixture;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use \InvalidArgumentException;

/**
 * LatLongTest
 */
class LatLongTest extends AbstractTestCase
{
    /**
     * @var LatLong A LatLong instance representing the top of Pen y Fan.
     */
    private $instance;

    /**
     * @var DatumFactory A DatumFactory instance.
     */
    private $datumFactory;

    /**
     * @var LatLongsFixture A fixture instance.
     */
    private $latLongsFixture;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        $this->datumFactory = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());
        $this->latLongsFixture = new LatLongsFixture();
        $this->instance = $this->latLongsFixture->getLatLongPenYFan();
    }

    public function testConstructorSetsPropertyValues(): void
    {
        $lat = 51.88328;
        $long = -3.43684;
        $height = 886;
        $datum = $this->datumFactory->createDefault();

        $instance = new LatLong($lat, $long, $height, $datum);

        $this->assertEquals($lat, $instance->getLatitude());
        $this->assertEquals($long, $instance->getLongitude());
        $this->assertEquals($height, $instance->getHeight());
        $this->assertEquals($datum, $instance->getDatum());
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
        $this->assertInstanceOf('ChrisCollins\GisUtils\Coordinate\LatLong', $object);
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
        $datumFactory = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());

        return array(
            array('latitude', 51.88328),
            array('longitude', -3.43684),
            array('height', 886),
            array('datum', $datumFactory->createDefault())
        );
    }

    public function testGetLatitudeRadiansConvertsLatitudeToRadians(): void
    {
        $this->assertEquals(deg2rad($this->instance->getLatitude()), $this->instance->getLatitudeRadians());
    }

    public function testSetLatitudeRadiansConvertsToDegreesAndSetsLatitude(): void
    {
        $radians = 0.89852459356531;
        $originalLatitude = $this->instance->getLatitude();
        $this->instance->setLatitudeRadians($radians);
        $this->assertEquals($radians, $this->instance->getLatitudeRadians());
        $this->assertEquals(rad2deg($radians), $this->instance->getLatitude());
        $this->assertNotEquals($originalLatitude, $this->instance->getLatitude());
    }

    public function testGetLongitudeRadiansConvertsLongitudeToRadians(): void
    {
        $this->assertEquals(deg2rad($this->instance->getLongitude()), $this->instance->getLongitudeRadians());
    }

    public function testSetLongitudeRadiansConvertsToDegreesAndSetsLongitude(): void
    {
        $radians = -3.182155;
        $originalLongitude = $this->instance->getLongitude();
        $this->instance->setLongitudeRadians($radians);
        $this->assertEquals($radians, $this->instance->getLongitudeRadians());
        $this->assertEquals(rad2deg($radians), $this->instance->getLongitude());
        $this->assertNotEquals($originalLongitude, $this->instance->getLongitude());
    }

    /**
     * testCalculateDistanceReturnsExpectedResultWithinPercentageTolerance
     *
     * @param LatLong $from The origin location.
     * @param LatLong $to The destination location.
     * @param float $expectedMetres The expected distance in metres (to 4 significant figures).
     *
     * @dataProvider getLatLongPairsWithDistances
     */
    public function testCalculateDistanceReturnsExpectedResultWithinPercentageTolerance(
        LatLong $from,
        LatLong $to,
        $expectedMetres
    ) {
        $tolerance = 0.3;
        $actualMetres = $from->calculateDistance($to);
        $this->assertEqualsWithinPercentageTolerance($expectedMetres, $actualMetres, $tolerance);
    }

    public function testCalculateDistanceThrowsExceptionIfDatumDiffersFromDestinationDatum(): void
    {
        $exceptionThrown = false;

        $destination = $this->latLongsFixture->getLatLongMillenniumStadium();
        $destination->setDatum($this->datumFactory->create(DatumFactory::DATUM_OSGB36));

        try {
            $this->instance->calculateDistance($destination);
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testCalculateDistanceReturnsZeroForIdenticalPoints(): void
    {
        $latLong1 = $this->latLongsFixture->getLatLongCardiffCastle();
        $latLong2 = $this->latLongsFixture->getLatLongCardiffCastle();

        $this->assertEquals(0, $latLong1->calculateDistance($latLong2));
    }

    /**
     * testCalculateDistanceVincentyReturnsExpectedResultWithinTolerance
     *
     * @param LatLong $from The origin location.
     * @param LatLong $to The destination location.
     * @param float $expectedMetres The expected distance in metres (to 4 significant figures).
     *
     * @dataProvider getLatLongPairsWithDistances
     */
    public function testCalculateDistanceVincentyReturnsExpectedResultWithinTolerance(
        LatLong $from,
        LatLong $to,
        $expectedMetres
    ) {
        $tolerance = 0.0001;
        $actualMetres = $from->calculateDistanceVincenty($to);
        $this->assertEqualsWithinTolerance($expectedMetres, $actualMetres, $tolerance);
    }

    public function testCalculateDistanceVincentyThrowsExceptionIfDatumDiffersFromDestinationDatum(): void
    {
        $exceptionThrown = false;

        $destination = $this->latLongsFixture->getLatLongMillenniumStadium();
        $destination->setDatum($this->datumFactory->create(DatumFactory::DATUM_OSGB36));

        try {
            $this->instance->calculateDistanceVincenty($destination);
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testCalculateDistanceVincentyReturnsZeroForIdenticalPoints(): void
    {
        $latLong1 = $this->latLongsFixture->getLatLongCardiffCastle();
        $latLong2 = $this->latLongsFixture->getLatLongCardiffCastle();

        $this->assertEquals(0, $latLong1->calculateDistanceVincenty($latLong2));
    }

    /**
     * testCalculateInitialAndFinalBearingReturnsExpectedValue
     *
     * @param LatLong $latLong The starting point.
     * @param LatLong $destination The destination.
     * @param float $expectedInitial The expected initial bearing.
     * @param float $expectedFinal The expected final bearing.
     *
     * @dataProvider getLatLongsWithBearings
     */
    public function testCalculateInitialAndFinalBearingReturnsExpectedValue(
        LatLong $latLong,
        LatLong $destination,
        $expectedInitialBearing,
        $expectedFinalBearing
    ) {
        $this->assertEquals($expectedInitialBearing, $latLong->calculateInitialBearing($destination));
        $this->assertEquals($expectedFinalBearing, $latLong->calculateFinalBearing($destination));
    }

    /**
     * testCalculateDestinationForBearingAndDistanceReturnsExpectedValue
     *
     * @param LatLong $latLong The starting point.
     * @param float $bearing The initial bearing.
     * @param float $distance The distance in metres to travel.
     * @param LatLong $expected The expected destination.
     *
     * @dataProvider getLatLongWithInitialBearingDistanceAndDestination
     */
    public function testCalculateDestinationForBearingAndDistanceReturnsExpectedValue(
        LatLong $latLong,
        $bearing,
        $distance,
        LatLong $expected
    ) {
        $actual = $latLong->calculateDestinationForBearingAndDistance($bearing, $distance);
        $tolerance = 0.3;

        $this->assertEqualsWithinTolerance($expected->getLatitude(), $actual->getLatitude(), $tolerance);
        $this->assertEqualsWithinTolerance($expected->getLongitude(), $actual->getLongitude(), $tolerance);

        $this->assertEquals($latLong->getHeight(), $actual->getHeight()); // Height remains constant.

        $this->assertEquals($expected->getDatum(), $actual->getDatum());
    }

    public function testToCartesianCoordinateReturnsExpectedResult(): void
    {
        $this->instance = new LatLong(52.65757, 1.71792, 24.7, $this->datumFactory->create(DatumFactory::DATUM_OSGB36));

        $cartesianCoordinate = $this->instance->toCartesianCoordinate();

        $significantFigures = 5;

        $this->assertEqualsWhenRounded(3874938.87953, $cartesianCoordinate->getX(), $significantFigures);
        $this->assertEqualsWhenRounded(116218.51749, $cartesianCoordinate->getY(), $significantFigures);
        $this->assertEqualsWhenRounded(5047168.18777, $cartesianCoordinate->getZ(), $significantFigures);
    }

    public function testToLatLongInDatumReturnsCloneOfSelfIfAlreadyInTargetDatum(): void
    {
        $converted = $this->instance->toLatLongInDatum($this->datumFactory->createDefault());

        $this->assertEquals($converted, $this->instance);
        $this->assertFalse($converted === $this->instance); // Not the same object.
    }

    public function testToLatLongInDatumReturnsConvertedLatLongIfNotAlreadyInTargetDatum(): void
    {
        $targetDatum = $this->datumFactory->create(DatumFactory::DATUM_OSGB36);

        $converted = $this->instance->toLatLongInDatum($targetDatum);

        $this->assertNotEquals($converted, $this->instance);
        $this->assertEquals($targetDatum, $converted->getDatum());
    }

    public function testToLatLongInDatumConvertsToBaseDatumThenConvertsToTargetDatumIfNotAlreadyInBaseDatum(): void
    {
        $this->instance->setDatum($this->datumFactory->create(DatumFactory::DATUM_ED50));

        $targetDatum = $this->datumFactory->create(DatumFactory::DATUM_OSGB36);

        $converted = $this->instance->toLatLongInDatum($targetDatum);

        $this->assertNotEquals($converted, $this->instance);
        $this->assertEquals($targetDatum, $converted->getDatum());
    }

    public function testToLatLongInDatumReturnsInitialValuesAfterBidirectionalTransform(): void
    {
        // WGS84 -> OSGB36 -> WGS84.
        $wgs84Datum = $this->datumFactory->create(DatumFactory::DATUM_WGS84);
        $osgb36Datum = $this->datumFactory->create(DatumFactory::DATUM_OSGB36);

        $osgb36LatLong = $this->instance->toLatLongInDatum($osgb36Datum);

        $this->assertEquals($osgb36Datum, $osgb36LatLong->getDatum());
        $this->assertNotEquals($this->instance, $osgb36LatLong);

        $wgs84LatLong = $this->instance->toLatLongInDatum($wgs84Datum);

        $this->assertEquals($wgs84Datum, $wgs84LatLong->getDatum());
        $this->assertNotEquals($osgb36LatLong, $wgs84LatLong);

        $tolerance = 0.015;

        $this->assertEqualsWithinTolerance($this->instance->getLatitude(), $wgs84LatLong->getLatitude(), $tolerance);
        $this->assertEqualsWithinTolerance($this->instance->getLongitude(), $wgs84LatLong->getLongitude(), $tolerance);
        $this->assertEqualsWithinTolerance($this->instance->getHeight(), $wgs84LatLong->getHeight(), $tolerance);
        $this->assertEquals($this->instance->getDatum(), $wgs84LatLong->getDatum());
    }

    public function testToStringMethodReturnsExpectedResult(): void
    {
        $expected = $this->instance->getLatitude() . ', ' . $this->instance->getLongitude();

        $this->assertEquals($expected, $this->instance->toString());
    }

    /**
     * Data provider to provide test values for calculating distances between points.
     *
     * @return array An array, each element an array containing 2 points and expected distance in metres.
     */
    public static function getLatLongPairsWithDistances()
    {
        $latLongsWithDistance = array();

        $latLongsFixture = new LatLongsFixture();

        foreach ($latLongsFixture->getPlaceNamePairs() as $pair) {
            $latLongsWithDistance[] = array(
                $latLongsFixture->getLatLongForPlace($pair[0]),
                $latLongsFixture->getLatLongForPlace($pair[1]),
                $latLongsFixture->getDistanceBetweenPlaces($pair[0], $pair[1])
            );
        }

        return $latLongsWithDistance;
    }

    /**
     * Data provider for testing with LatLongs, initial bearings and distances.
     *
     * @return array An array, each element an array with a LatLong, bearing, distance in metres and destination.
     */
    public static function getLatLongWithInitialBearingDistanceAndDestination()
    {
        $dataArray = array();

        $latLongsFixture = new LatLongsFixture();

        foreach ($latLongsFixture->getPlaceNamePairs() as $pair) {
            $dataArray[] = array(
                $latLongsFixture->getLatLongForPlace($pair[0]),
                $latLongsFixture->getInitialBearingBetweenPlaces($pair[0], $pair[1]),
                $latLongsFixture->getDistanceBetweenPlaces($pair[0], $pair[1]),
                $latLongsFixture->getLatLongForPlace($pair[1])
            );
        }

        return $dataArray;
    }

    /**
     * Data provider for testing with LatLongs and bearings.
     *
     * @return array An array, each element an array of two LatLongs and initial and final bearings between them.
     */
    public static function getLatLongsWithBearings()
    {
        $dataArray = array();

        $latLongsFixture = new LatLongsFixture();

        foreach ($latLongsFixture->getPlaceNamePairs() as $pair) {
            $dataArray[] = array(
                $latLongsFixture->getLatLongForPlace($pair[0]),
                $latLongsFixture->getLatLongForPlace($pair[1]),
                $latLongsFixture->getInitialBearingBetweenPlaces($pair[0], $pair[1]),
                $latLongsFixture->getFinalBearingBetweenPlaces($pair[0], $pair[1])
            );
        }

        return $dataArray;
    }
}
