<?php

namespace ChrisCollins\GisUtils\Test\Fixture;

use ChrisCollins\GisUtils\Coordinate\LatLong;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use \InvalidArgumentException;

/**
 * LatLongsFixture
 *
 * Test fixture to provide example LatLong data.
 */
class LatLongsFixture
{
    /**#@+
     * @var string Constant for place name.
     */
    const PLACE_PEN_Y_FAN = 'Pen Y Fan';
    const PLACE_CARDIFF_CASTLE = 'Cardiff Castle';
    const PLACE_MILLENNIUM_STADIUM = 'Millennium Stadium';
    const PLACE_EIFFEL_TOWER = 'Eiffel Tower';
    const PLACE_SYDNEY_OPERA_HOUSE = 'Sydney Opera House';
    const PLACE_GOOGLE_HQ = 'Google HQ';
    /**#@-*/

    /**
     * @var array Array of all supported place names.
     */
    protected static $places = array(
        self::PLACE_PEN_Y_FAN,
        self::PLACE_CARDIFF_CASTLE,
        self::PLACE_MILLENNIUM_STADIUM,
        self::PLACE_EIFFEL_TOWER,
        self::PLACE_SYDNEY_OPERA_HOUSE,
        self::PLACE_GOOGLE_HQ
    );

    /**
     * @var array Array of lat/long data keyed on place names.
     */
    protected static $data = array(
        self::PLACE_PEN_Y_FAN => array(
            'latitude' => 51.88328,
            'longitude' => -3.43684,
            'height' => 886,
            'distances' => array(
                self::PLACE_CARDIFF_CASTLE => 48030.514710868,
                self::PLACE_MILLENNIUM_STADIUM => 48394.282981964,
                self::PLACE_EIFFEL_TOWER => 528385.12363824,
                self::PLACE_SYDNEY_OPERA_HOUSE => 17159457.577066,
                self::PLACE_GOOGLE_HQ => 8465001.2823866
            ),
            'initialBearings' => array(
                self::PLACE_CARDIFF_CASTLE => 158.43584174576,
                self::PLACE_MILLENNIUM_STADIUM => 158.68209828541,
                self::PLACE_EIFFEL_TOWER => 127.37394057004,
                self::PLACE_SYDNEY_OPERA_HOUSE => 55.258898058472,
                self::PLACE_GOOGLE_HQ => 314.06825170038
            ),
            'finalBearings' => array(
                self::PLACE_CARDIFF_CASTLE => 158.63566557499,
                self::PLACE_MILLENNIUM_STADIUM => 158.88122422768,
                self::PLACE_EIFFEL_TOWER => 131.79111107212,
                self::PLACE_SYDNEY_OPERA_HOUSE => 142.35269472657,
                self::PLACE_GOOGLE_HQ => 213.9488668296
            )
        ),
        self::PLACE_CARDIFF_CASTLE => array(
            'latitude' => 51.481667,
            'longitude' => -3.182155,
            'height' => 0,
            'distances' => array(
                self::PLACE_PEN_Y_FAN => 48030.514710869,
                self::PLACE_MILLENNIUM_STADIUM => 418.83923327973,
                self::PLACE_EIFFEL_TOWER => 487882.44087311,
                self::PLACE_SYDNEY_OPERA_HOUSE => 17170306.956765,
                self::PLACE_GOOGLE_HQ => 8508792.0009591
            ),
            'initialBearings' => array(
                self::PLACE_PEN_Y_FAN => 338.63566557499,
                self::PLACE_MILLENNIUM_STADIUM => 188.40051835967,
                self::PLACE_EIFFEL_TOWER => 124.66518311375,
                self::PLACE_SYDNEY_OPERA_HOUSE => 56.337714342575,
                self::PLACE_GOOGLE_HQ => 314.31200734291
            ),
            'finalBearings' => array(
                self::PLACE_PEN_Y_FAN => 338.43584174576,
                self::PLACE_MILLENNIUM_STADIUM => 188.39982751046,
                self::PLACE_EIFFEL_TOWER => 128.87331087321,
                self::PLACE_SYDNEY_OPERA_HOUSE => 141.37811872097,
                self::PLACE_GOOGLE_HQ => 214.1321091392
            )
        ),
        self::PLACE_MILLENNIUM_STADIUM => array(
            'latitude' => 51.477943,
            'longitude' => -3.183038,
            'height' => 0,
            'distances' => array(
                self::PLACE_PEN_Y_FAN => 48394.282981964,
                self::PLACE_CARDIFF_CASTLE => 418.83923327999,
                self::PLACE_EIFFEL_TOWER => 487697.88033125,
                self::PLACE_SYDNEY_OPERA_HOUSE => 17170589.659445,
                self::PLACE_GOOGLE_HQ => 8509037.7755801
            ),
            'initialBearings' => array(
                self::PLACE_PEN_Y_FAN => 338.88122422768,
                self::PLACE_CARDIFF_CASTLE => 8.3998275104594,
                self::PLACE_EIFFEL_TOWER => 124.6203863911,
                self::PLACE_SYDNEY_OPERA_HOUSE => 56.342872569329,
                self::PLACE_GOOGLE_HQ => 314.31205824855
            ),
            'finalBearings' => array(
                self::PLACE_PEN_Y_FAN => 338.68209828541,
                self::PLACE_CARDIFF_CASTLE => 8.4005183596748,
                self::PLACE_EIFFEL_TOWER => 128.82907595408,
                self::PLACE_SYDNEY_OPERA_HOUSE => 141.37163621598,
                self::PLACE_GOOGLE_HQ => 214.13524692428
            )
        ),
        self::PLACE_EIFFEL_TOWER => array(
            'latitude' => 48.8582,
            'longitude' => 2.294407,
            'height' => 0,
            'distances' => array(
                self::PLACE_PEN_Y_FAN => 528385.12363824,
                self::PLACE_CARDIFF_CASTLE => 487882.44087311,
                self::PLACE_MILLENNIUM_STADIUM => 487697.88033125,
                self::PLACE_SYDNEY_OPERA_HOUSE => 16960846.073721,
                self::PLACE_GOOGLE_HQ => 8989724.3991109
            ),
            'initialBearings' => array(
                self::PLACE_PEN_Y_FAN => 311.79111107212,
                self::PLACE_CARDIFF_CASTLE => 308.87331087321,
                self::PLACE_MILLENNIUM_STADIUM => 308.82907595408,
                self::PLACE_SYDNEY_OPERA_HOUSE => 68.476306591903,
                self::PLACE_GOOGLE_HQ => 318.37171168188
            ),
            'finalBearings' => array(
                self::PLACE_PEN_Y_FAN => 307.37394057004,
                self::PLACE_CARDIFF_CASTLE => 304.66518311375,
                self::PLACE_MILLENNIUM_STADIUM => 304.6203863911,
                self::PLACE_SYDNEY_OPERA_HOUSE => 132.52203771679,
                self::PLACE_GOOGLE_HQ => 213.3892094625
            )
        ),
        self::PLACE_SYDNEY_OPERA_HOUSE => array(
            'latitude' => -33.856553,
            'longitude' => 151.214696,
            'height' => 0,
            'distances' => array(
                self::PLACE_PEN_Y_FAN => 17159457.577066,
                self::PLACE_CARDIFF_CASTLE => 17170306.956765,
                self::PLACE_MILLENNIUM_STADIUM => 17170589.659445,
                self::PLACE_EIFFEL_TOWER => 16960846.073721,
                self::PLACE_GOOGLE_HQ => 11939773.640109
            ),
            'initialBearings' => array(
                self::PLACE_PEN_Y_FAN => 322.35269472657,
                self::PLACE_CARDIFF_CASTLE => 321.37811872097,
                self::PLACE_MILLENNIUM_STADIUM => 321.37163621598,
                self::PLACE_EIFFEL_TOWER => 312.52203771679,
                self::PLACE_GOOGLE_HQ => 56.23369080883
            ),
            'finalBearings' => array(
                self::PLACE_PEN_Y_FAN => 235.25889805847,
                self::PLACE_CARDIFF_CASTLE => 236.33771434258,
                self::PLACE_MILLENNIUM_STADIUM => 236.34287256933,
                self::PLACE_EIFFEL_TOWER => 248.4763065919,
                self::PLACE_GOOGLE_HQ => 60.372823628798
            )
        ),
        self::PLACE_GOOGLE_HQ => array(
            'latitude' => 37.422045,
            'longitude' => -122.084347,
            'height' => 0,
            'distances' => array(
                self::PLACE_PEN_Y_FAN => 8465001.2823866,
                self::PLACE_CARDIFF_CASTLE => 8508792.0009591,
                self::PLACE_MILLENNIUM_STADIUM => 8509037.7755801,
                self::PLACE_EIFFEL_TOWER => 8989724.3991109,
                self::PLACE_SYDNEY_OPERA_HOUSE => 11939773.640109
            ),
            'initialBearings' => array(
                self::PLACE_PEN_Y_FAN => 33.948866829602,
                self::PLACE_CARDIFF_CASTLE => 34.132109139205,
                self::PLACE_MILLENNIUM_STADIUM => 34.13524692428,
                self::PLACE_EIFFEL_TOWER => 33.389209462498,
                self::PLACE_SYDNEY_OPERA_HOUSE => 240.3728236288
            ),
            'finalBearings' => array(
                self::PLACE_PEN_Y_FAN => 134.06825170038,
                self::PLACE_CARDIFF_CASTLE => 134.31200734291,
                self::PLACE_MILLENNIUM_STADIUM => 134.31205824855,
                self::PLACE_EIFFEL_TOWER => 138.37171168188,
                self::PLACE_SYDNEY_OPERA_HOUSE => 236.23369080883
            )
        ),
    );

    /**
     * @var Datum A Datum that LatLongs will be initialised with.
     */
    private $datum;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $datumFactory = new DatumFactory(new EllipsoidFactory(), new HelmertTransformFactory());
        $this->datum = $datumFactory->createDefault();
    }

    /**
     * Get a LatLong representing Pen y Fan.
     *
     * @return LatLong A LatLong.
     */
    public function getLatLongPenYFan()
    {
        return $this->getLatLongForPlace(self::PLACE_PEN_Y_FAN);
    }

    /**
     * Get a LatLong representing Cardiff Castle.
     *
     * @return LatLong A LatLong.
     */
    public function getLatLongCardiffCastle()
    {
        return $this->getLatLongForPlace(self::PLACE_CARDIFF_CASTLE);
    }

    /**
     * Get a LatLong representing The Millennium Stadium.
     *
     * @return LatLong A LatLong.
     */
    public function getLatLongMillenniumStadium()
    {
        return $this->getLatLongForPlace(self::PLACE_MILLENNIUM_STADIUM);
    }

    /**
     * Get a LatLong representing The Eiffel Tower.
     *
     * @return LatLong A LatLong.
     */
    public function getLatLongEiffelTower()
    {
        return $this->getLatLongForPlace(self::PLACE_EIFFEL_TOWER);
    }

    /**
     * Get a LatLong representing The Sydney Opera House.
     *
     * @return LatLong A LatLong.
     */
    public function getLatLongSydneyOperaHouse()
    {
        return $this->getLatLongForPlace(self::PLACE_SYDNEY_OPERA_HOUSE);
    }

    /**
     * Get a LatLong representing Google HQ.
     *
     * @return LatLong A LatLong.
     */
    public function getLatLongGoogleHq()
    {
        return $this->getLatLongForPlace(self::PLACE_GOOGLE_HQ);
    }

    /**
     * Get a LatLong for the given place name.
     *
     * @param string $name The name of the place to get a LatLong for.
     *
     * @return LatLong The LatLong.
     *
     * @throws InvalidArgumentException If the place name was invalid.
     */
    public function getLatLongForPlace($name)
    {
        if (!isset(self::$data[$name])) {
            throw new InvalidArgumentException('Coordinates for ' . $name . ' are not available on this fixture.');
        }

        $latLongData = self::$data[$name];

        return new LatLong($latLongData['latitude'], $latLongData['longitude'], $latLongData['height'], $this->datum);
    }

    /**
     * Get the distance in metre between two places.
     *
     * @param string $name1 The name of the first place.
     * @param string $name1 The name of the second place.
     *
     * @return float The distance between the two places.
     */
    public function getDistanceBetweenPlaces($name1, $name2)
    {
        if (!isset(self::$data[$name1])) {
            throw new InvalidArgumentException('Distance data for ' . $name1 . ' is not available on this fixture.');
        }

        $distanceData = self::$data[$name1]['distances'];

        if (!isset($distanceData[$name2])) {
            throw new InvalidArgumentException('Distance data for ' . $name2 . ' is not available on this fixture.');
        }

        return $distanceData[$name2];
    }

    /**
     * Get the initial bearing to travel along to reach a destination point.
     *
     * @param string $name1 The name of the first place.
     * @param string $name1 The name of the second place.
     *
     * @return float The initial bearing.
     */
    public function getInitialBearingBetweenPlaces($name1, $name2)
    {
        if (!isset(self::$data[$name1])) {
            throw new InvalidArgumentException(
                'Initial bearing data for ' . $name1 . ' is not available on this fixture.'
            );
        }

        $bearingData = self::$data[$name1]['initialBearings'];

        if (!isset($bearingData[$name2])) {
            throw new InvalidArgumentException(
                'Initial bearing data for ' . $name2 . ' is not available on this fixture.'
            );
        }

        return $bearingData[$name2];
    }

    /**
     * Get the final bearing to travel along to reach a destination point.
     *
     * @param string $name1 The name of the first place.
     * @param string $name1 The name of the second place.
     *
     * @return float The final bearing.
     */
    public function getFinalBearingBetweenPlaces($name1, $name2)
    {
        if (!isset(self::$data[$name1])) {
            throw new InvalidArgumentException(
                'Final bearing data for ' . $name1 . ' is not available on this fixture.'
            );
        }

        $bearingData = self::$data[$name1]['finalBearings'];

        if (!isset($bearingData[$name2])) {
            throw new InvalidArgumentException(
                'Final bearing data for ' . $name2 . ' is not available on this fixture.'
            );
        }

        return $bearingData[$name2];
    }

    /**
     * Get an array of places in this fixture.
     *
     * @return array An array of place names.
     */
    public function getSupportedPlaces()
    {
        return self::$places;
    }

    /**
     * Get an array of all possible place name pairs.
     *
     * @return array An array of place name pairs.
     */
    public function getPlaceNamePairs()
    {
        $pairs = array();

        $fixture = new LatLongsFixture();

        $places = $fixture->getSupportedPlaces();

        foreach ($places as $place1) {
            foreach ($places as $place2) {
                if ($place1 !== $place2) {
                    $pairs[] = array($place1, $place2);
                }
            }
        }

        return $pairs;
    }
}
