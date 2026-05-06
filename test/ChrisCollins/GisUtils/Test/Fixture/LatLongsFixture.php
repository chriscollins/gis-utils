<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Fixture;

use ChrisCollins\GisUtils\Coordinate\LatLong;
use ChrisCollins\GisUtils\Datum\Datum;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use InvalidArgumentException;

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
    public const PLACE_PEN_Y_FAN = 'Pen Y Fan';

    public const PLACE_CARDIFF_CASTLE = 'Cardiff Castle';

    public const PLACE_MILLENNIUM_STADIUM = 'Millennium Stadium';

    public const PLACE_EIFFEL_TOWER = 'Eiffel Tower';

    public const PLACE_SYDNEY_OPERA_HOUSE = 'Sydney Opera House';

    public const PLACE_GOOGLE_HQ = 'Google HQ';

    /**#@-*/

    /** @var string[] Array of all supported place names. */
    protected static $places = [
        self::PLACE_PEN_Y_FAN,
        self::PLACE_CARDIFF_CASTLE,
        self::PLACE_MILLENNIUM_STADIUM,
        self::PLACE_EIFFEL_TOWER,
        self::PLACE_SYDNEY_OPERA_HOUSE,
        self::PLACE_GOOGLE_HQ
    ];

    /** @var array<string,array<string,mixed>> Array of lat/long data keyed on place names. */
    protected static $data = [
        self::PLACE_PEN_Y_FAN => [
            'latitude' => 51.88328,
            'longitude' => -3.43684,
            'height' => 886,
            'distances' => [
                self::PLACE_CARDIFF_CASTLE => 48030.514710868,
                self::PLACE_MILLENNIUM_STADIUM => 48394.282981964,
                self::PLACE_EIFFEL_TOWER => 528385.12363824,
                self::PLACE_SYDNEY_OPERA_HOUSE => 17159457.577066,
                self::PLACE_GOOGLE_HQ => 8465001.2823866
            ],
            'initialBearings' => [
                self::PLACE_CARDIFF_CASTLE => 158.43584174576256,
                self::PLACE_MILLENNIUM_STADIUM => 158.68209828541364,
                self::PLACE_EIFFEL_TOWER => 127.37394057003593,
                self::PLACE_SYDNEY_OPERA_HOUSE => 55.258898058472084,
                self::PLACE_GOOGLE_HQ => 314.06825170038167
            ],
            'finalBearings' => [
                self::PLACE_CARDIFF_CASTLE => 158.635665574992,
                self::PLACE_MILLENNIUM_STADIUM => 158.88122422767788,
                self::PLACE_EIFFEL_TOWER => 131.7911110721182,
                self::PLACE_SYDNEY_OPERA_HOUSE => 142.3526947265667,
                self::PLACE_GOOGLE_HQ => 213.94886682960163
            ]
        ],
        self::PLACE_CARDIFF_CASTLE => [
            'latitude' => 51.481667,
            'longitude' => -3.182155,
            'height' => 0,
            'distances' => [
                self::PLACE_PEN_Y_FAN => 48030.514710869,
                self::PLACE_MILLENNIUM_STADIUM => 418.83923327973,
                self::PLACE_EIFFEL_TOWER => 487882.44087311,
                self::PLACE_SYDNEY_OPERA_HOUSE => 17170306.956765,
                self::PLACE_GOOGLE_HQ => 8508792.0009591
            ],
            'initialBearings' => [
                self::PLACE_PEN_Y_FAN => 338.635665574992,
                self::PLACE_MILLENNIUM_STADIUM => 188.40051835967483,
                self::PLACE_EIFFEL_TOWER => 124.66518311374551,
                self::PLACE_SYDNEY_OPERA_HOUSE => 56.33771434257528,
                self::PLACE_GOOGLE_HQ => 314.3120073429133
            ],
            'finalBearings' => [
                self::PLACE_PEN_Y_FAN => 338.43584174576256,
                self::PLACE_MILLENNIUM_STADIUM => 188.39982751045937,
                self::PLACE_EIFFEL_TOWER => 128.87331087320882,
                self::PLACE_SYDNEY_OPERA_HOUSE => 141.37811872096603,
                self::PLACE_GOOGLE_HQ => 214.13210913920466
            ]
        ],
        self::PLACE_MILLENNIUM_STADIUM => [
            'latitude' => 51.477943,
            'longitude' => -3.183038,
            'height' => 0,
            'distances' => [
                self::PLACE_PEN_Y_FAN => 48394.282981964,
                self::PLACE_CARDIFF_CASTLE => 418.83923327999,
                self::PLACE_EIFFEL_TOWER => 487697.88033125,
                self::PLACE_SYDNEY_OPERA_HOUSE => 17170589.659445,
                self::PLACE_GOOGLE_HQ => 8509037.7755801
            ],
            'initialBearings' => [
                self::PLACE_PEN_Y_FAN => 338.8812242276779,
                self::PLACE_CARDIFF_CASTLE => 8.399827510459374,
                self::PLACE_EIFFEL_TOWER => 124.62038639110193,
                self::PLACE_SYDNEY_OPERA_HOUSE => 56.34287256932885,
                self::PLACE_GOOGLE_HQ => 314.31205824855084
            ],
            'finalBearings' => [
                self::PLACE_PEN_Y_FAN => 338.68209828541364,
                self::PLACE_CARDIFF_CASTLE => 8.400518359674834,
                self::PLACE_EIFFEL_TOWER => 128.8290759540758,
                self::PLACE_SYDNEY_OPERA_HOUSE => 141.37163621598125,
                self::PLACE_GOOGLE_HQ => 214.13524692427973
            ]
        ],
        self::PLACE_EIFFEL_TOWER => [
            'latitude' => 48.8582,
            'longitude' => 2.294407,
            'height' => 0,
            'distances' => [
                self::PLACE_PEN_Y_FAN => 528385.12363824,
                self::PLACE_CARDIFF_CASTLE => 487882.44087311,
                self::PLACE_MILLENNIUM_STADIUM => 487697.88033125,
                self::PLACE_SYDNEY_OPERA_HOUSE => 16960846.073721,
                self::PLACE_GOOGLE_HQ => 8989724.3991109
            ],
            'initialBearings' => [
                self::PLACE_PEN_Y_FAN => 311.7911110721182,
                self::PLACE_CARDIFF_CASTLE => 308.8733108732088,
                self::PLACE_MILLENNIUM_STADIUM => 308.8290759540758,
                self::PLACE_SYDNEY_OPERA_HOUSE => 68.4763065919035,
                self::PLACE_GOOGLE_HQ => 318.371711681884
            ],
            'finalBearings' => [
                self::PLACE_PEN_Y_FAN => 307.3739405700359,
                self::PLACE_CARDIFF_CASTLE => 304.6651831137455,
                self::PLACE_MILLENNIUM_STADIUM => 304.62038639110193,
                self::PLACE_SYDNEY_OPERA_HOUSE => 132.52203771679189,
                self::PLACE_GOOGLE_HQ => 213.38920946249783
            ]
        ],
        self::PLACE_SYDNEY_OPERA_HOUSE => [
            'latitude' => -33.856553,
            'longitude' => 151.214696,
            'height' => 0,
            'distances' => [
                self::PLACE_PEN_Y_FAN => 17159457.577066,
                self::PLACE_CARDIFF_CASTLE => 17170306.956765,
                self::PLACE_MILLENNIUM_STADIUM => 17170589.659445,
                self::PLACE_EIFFEL_TOWER => 16960846.073721,
                self::PLACE_GOOGLE_HQ => 11939773.640109
            ],
            'initialBearings' => [
                self::PLACE_PEN_Y_FAN => 322.3526947265667,
                self::PLACE_CARDIFF_CASTLE => 321.37811872096603,
                self::PLACE_MILLENNIUM_STADIUM => 321.37163621598125,
                self::PLACE_EIFFEL_TOWER => 312.5220377167919,
                self::PLACE_GOOGLE_HQ => 56.23369080882952
            ],
            'finalBearings' => [
                self::PLACE_PEN_Y_FAN => 235.25889805847208,
                self::PLACE_CARDIFF_CASTLE => 236.33771434257528,
                self::PLACE_MILLENNIUM_STADIUM => 236.34287256932885,
                self::PLACE_EIFFEL_TOWER => 248.4763065919035,
                self::PLACE_GOOGLE_HQ => 60.37282362879796
            ]
        ],
        self::PLACE_GOOGLE_HQ => [
            'latitude' => 37.422045,
            'longitude' => -122.084347,
            'height' => 0,
            'distances' => [
                self::PLACE_PEN_Y_FAN => 8465001.2823866,
                self::PLACE_CARDIFF_CASTLE => 8508792.0009591,
                self::PLACE_MILLENNIUM_STADIUM => 8509037.7755801,
                self::PLACE_EIFFEL_TOWER => 8989724.3991109,
                self::PLACE_SYDNEY_OPERA_HOUSE => 11939773.640109
            ],
            'initialBearings' => [
                self::PLACE_PEN_Y_FAN => 33.94886682960163,
                self::PLACE_CARDIFF_CASTLE => 34.13210913920466,
                self::PLACE_MILLENNIUM_STADIUM => 34.13524692427973,
                self::PLACE_EIFFEL_TOWER => 33.38920946249783,
                self::PLACE_SYDNEY_OPERA_HOUSE => 240.37282362879793
            ],
            'finalBearings' => [
                self::PLACE_PEN_Y_FAN => 134.06825170038167,
                self::PLACE_CARDIFF_CASTLE => 134.31200734291332,
                self::PLACE_MILLENNIUM_STADIUM => 134.31205824855084,
                self::PLACE_EIFFEL_TOWER => 138.371711681884,
                self::PLACE_SYDNEY_OPERA_HOUSE => 236.23369080882952
            ]
        ],
    ];

    /** @var Datum A Datum that LatLongs will be initialised with. */
    private Datum $datum;

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
     */
    public function getLatLongPenYFan(): LatLong
    {
        return $this->getLatLongForPlace(self::PLACE_PEN_Y_FAN);
    }

    /**
     * Get a LatLong representing Cardiff Castle.
     */
    public function getLatLongCardiffCastle(): LatLong
    {
        return $this->getLatLongForPlace(self::PLACE_CARDIFF_CASTLE);
    }

    /**
     * Get a LatLong representing The Millennium Stadium.
     */
    public function getLatLongMillenniumStadium(): LatLong
    {
        return $this->getLatLongForPlace(self::PLACE_MILLENNIUM_STADIUM);
    }

    /**
     * Get a LatLong representing The Eiffel Tower.
     */
    public function getLatLongEiffelTower(): LatLong
    {
        return $this->getLatLongForPlace(self::PLACE_EIFFEL_TOWER);
    }

    /**
     * Get a LatLong representing The Sydney Opera House.
     */
    public function getLatLongSydneyOperaHouse(): LatLong
    {
        return $this->getLatLongForPlace(self::PLACE_SYDNEY_OPERA_HOUSE);
    }

    /**
     * Get a LatLong representing Google HQ.
     */
    public function getLatLongGoogleHq(): LatLong
    {
        return $this->getLatLongForPlace(self::PLACE_GOOGLE_HQ);
    }

    /**
     * Get a LatLong for the given place name.
     *
     * @param string $name The name of the place to get a LatLong for.
     *
     *
     * @throws InvalidArgumentException If the place name was invalid.
     */
    public function getLatLongForPlace($name): LatLong
    {
        if (!isset(self::$data[$name])) {
            throw new InvalidArgumentException('Coordinates for ' . $name . ' are not available on this fixture.');
        }

        $latLongData = self::$data[$name];

        return new LatLong($latLongData['latitude'], $latLongData['longitude'], $latLongData['height'], $this->datum);
    }

    /**
     * Get the distance in metres between two places.
     */
    public function getDistanceBetweenPlaces(string $name1, string $name2): float
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
     */
    public function getInitialBearingBetweenPlaces(string $name1, string $name2): float
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
     */
    public function getFinalBearingBetweenPlaces(string $name1, string $name2): float
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
     * @return string[]
     */
    public function getSupportedPlaces(): array
    {
        return self::$places;
    }

    /**
     * Get an array of all possible place name pairs.
     *
     * @return array<string[]>
     */
    public function getPlaceNamePairs(): array
    {
        $pairs = [];

        $fixture = new LatLongsFixture();

        $places = $fixture->getSupportedPlaces();

        foreach ($places as $place1) {
            foreach ($places as $place2) {
                if ($place1 !== $place2) {
                    $pairs[] = [$place1, $place2];
                }
            }
        }

        return $pairs;
    }
}
