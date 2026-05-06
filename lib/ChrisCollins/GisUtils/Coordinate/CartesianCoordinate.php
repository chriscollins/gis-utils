<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Coordinate;

use ChrisCollins\GisUtils\Datum\Datum;
use Stringable;

/**
 * CartesianCoordinate
 *
 * Class to represent a three-dimensional Cartesian coordinate.
 */
class CartesianCoordinate implements Stringable
{
    public function __construct(
        private float $x,
        private float $y,
        private float $z,
        private Datum $datum
    ) {
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function setX(float $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function setY(float $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getZ(): float
    {
        return $this->z;
    }

    public function setZ(float $z): self
    {
        $this->z = $z;

        return $this;
    }

    public function getDatum(): Datum
    {
        return $this->datum;
    }

    public function setDatum(Datum $datum): self
    {
        $this->datum = $datum;

        return $this;
    }

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
        $latRadPrime = 2 * M_PI;

        $transverseRadiusCurvature = 0;

        while (abs($latRad - $latRadPrime) > $precision) {
            $sinLatRad = sin($latRad);
            $transverseRadiusCurvature =
                $semiMajorAxis / sqrt(1 - $ellipsoidEccentricitySquared * $sinLatRad * $sinLatRad);

            $latRadPrime = $latRad;
            $latRad = atan2($this->z + $ellipsoidEccentricitySquared * $transverseRadiusCurvature * $sinLatRad, $p);
        }

        $longRad = atan2($this->y, $this->x);
        $height = $p / cos($latRad) - $transverseRadiusCurvature;

        return new LatLong($latRad, $longRad, $height, clone ($this->datum), true);
    }

    public function __toString(): string
    {
        return $this->x . ', ' . $this->y . ', ' . $this->z;
    }
}
