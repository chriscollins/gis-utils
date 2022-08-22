<?php

namespace ChrisCollins\GisUtils\Coordinate;

use ChrisCollins\GisUtils\Datum\Datum;

/**
 * CartesianCoordinate
 *
 * Class to represent a three-dimensional Cartesian coordinate.
 */
class CartesianCoordinate
{
    /**
     * @var float The X coordinate.
     */
    private float $x;

    /**
     * @var float The Y coordinate.
     */
    private float $y;

    /**
     * @var float The Z coordinate.
     */
    private float $z;

    /**
     * @var Datum The datum that the coordinate uses.
     */
    private Datum $datum;

    /**
     * Constructor.
     *
     * @param float $x The X coordinate.
     * @param float $y The Y coordinate.
     * @param float $z The Z coordinate.
     * @param Datum $datum The datum that the coordinate uses.
     */
    public function __construct(float $x, float $y, float $z, Datum $datum)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->datum = $datum;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getX(): float
    {
        return $this->x;
    }

    /**
     * Mutator method.
     *
     * @param float $x The new value of the property.
     *
     * @return CartesianCoordinate This object.
     */
    public function setX(float $x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getY(): float
    {
        return $this->y;
    }

    /**
     * Mutator method.
     *
     * @param float $y The new value of the property.
     *
     * @return CartesianCoordinate This object.
     */
    public function setY(float $y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getZ(): float
    {
        return $this->z;
    }

    /**
     * Mutator method.
     *
     * @param float $z The new value of the property.
     *
     * @return CartesianCoordinate This object.
     */
    public function setZ(float $z)
    {
        $this->z = $z;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return Datum The value of the property.
     */
    public function getDatum(): Datum
    {
        return $this->datum;
    }

    /**
     * Mutator method.
     *
     * @param Datum $datum The new value of the property.
     *
     * @return CartesianCoordinate This object.
     */
    public function setDatum(Datum $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    /**
     * Convert this CartesianCoordinate to a LatLong.
     *
     * @return LatLong A LatLong representation of this coordinate.
     */
    public function toLatLong(): LatLong
    {
        $semiMajorAxis = $this->datum->getEllipsoid()
            ->getSemiMajorAxisMetres();

        $semiMinorAxis = $this->datum->getEllipsoid()
            ->getSemiMinorAxisMetres();

        $precision = 4 / $semiMajorAxis; // Result accurate to around 4 metres.

        $semiMajorAxisSquared = $semiMajorAxis * $semiMajorAxis;
        $semiMinorAxisSquared = $semiMinorAxis * $semiMinorAxis;

        $ellipsoidEccentricitySquared = ($semiMajorAxisSquared - $semiMinorAxisSquared) / $semiMajorAxisSquared;

        $p = sqrt($this->x * $this->x + $this->y * $this->y);

        $latRad = atan2($this->z, $p * (1 - $ellipsoidEccentricitySquared));
        $latRadPrime = 2 * pi();

        while (abs($latRad - $latRadPrime) > $precision) {
            $sinLatRad = sin($latRad);
            $transverseRadiusCurvature =
                $semiMajorAxis / sqrt(1 - $ellipsoidEccentricitySquared * $sinLatRad * $sinLatRad);

            $latRadPrime = $latRad;
            $latRad = atan2($this->z + $ellipsoidEccentricitySquared * $transverseRadiusCurvature * $sinLatRad, $p);
        }

        $longRad = atan2($this->y, $this->x);
        $height = $p / cos($latRad) - $transverseRadiusCurvature;

        return new LatLong($latRad, $longRad, $height, clone($this->datum), true);
    }

    /**
     * Get a string representation of the object.
     *
     * @return string A string representation of the object.
     */
    public function toString(): string
    {
        return $this->x . ', ' . $this->y . ', ' . $this->z;
    }
}
