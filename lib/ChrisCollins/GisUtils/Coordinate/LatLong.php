<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Coordinate;

use ChrisCollins\GisUtils\Datum\Datum;
use ChrisCollins\GisUtils\Equation\HelmertTransform;
use InvalidArgumentException;
use Stringable;

/**
 * LatLong
 *
 * A class to represent a latitude/longitude coordinate.
 */
class LatLong implements Stringable
{
    /**
     * @var int The mean radius of Earth in metres.  Not datum-specific, this is used in less-accurate distance
     *          calculations, as it treats the Earth as a sphere.
     */
    private const EARTH_MEAN_RADIUS_METRES = 6371000;

    /** @var float The latitude in decimal degrees. */
    private float $latitude;

    /** @var float The longitude in decimal degrees. */
    private float $longitude;

    /**
     * Constructor.
     *
     * @param bool $asRadians If true, the input latitude and longitude are treated as radians, not decimal degrees.
     */
    public function __construct(
        float $latitude,
        float $longitude,
        private ?float $height,
        private Datum $datum,
        bool $asRadians = false
    ) {
        if ($asRadians) {
            $this->setLatitudeRadians($latitude);
            $this->setLongitudeRadians($longitude);
        } else {
            $this->latitude = $latitude;
            $this->longitude = $longitude;
        }
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLatitudeRadians(): float
    {
        return deg2rad($this->latitude);
    }

    public function setLatitudeRadians(float $latitudeRadians): self
    {
        $this->latitude = rad2deg($latitudeRadians);

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLongitudeRadians(): float
    {
        return deg2rad($this->longitude);
    }

    public function setLongitudeRadians(float $longitudeRadians): self
    {
        $this->longitude = rad2deg($longitudeRadians);

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

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

    /**
     * Calculate the distance in metres to another LatLong coordinate.
     *
     * This is a relatively quick calculation to perform, but as it treats the Earth as a sphere, rather than an
     * ellipsoid, it is only accurate within approximately 0.55%.
     *
     * @param LatLong $destination The coordinate to measure the distance to.
     *
     * @throws InvalidArgumentException If the datum of this object does not match that of the $destination LatLong.
     */
    public function calculateDistance(LatLong $destination): float
    {
        if ($destination->getDatum() != $this->datum) {
            throw new InvalidArgumentException('Datums must match to calculate distance.');
        }

        // Note, we don't use a datum-specific radius here, as we are treating the Earth as a sphere, not an ellipsoid.
        $earthRadius = self::EARTH_MEAN_RADIUS_METRES;

        // Determine the distance using the spherical law of cosines.
        $latRad = $this->getLatitudeRadians();
        $longRad = $this->getLongitudeRadians();

        $cosLatRad = cos($latRad);
        $sinLatRad = sin($latRad);

        $destLatRad = $destination->getLatitudeRadians();
        $destLongRad = $destination->getLongitudeRadians();

        $destCostLatRad = cos($destLatRad);
        $destSinLatRad = sin($destLatRad);

        $cosLongRadDifference = cos($destLongRad - $longRad);

        return $earthRadius * acos(
            $cosLatRad * $destCostLatRad * $cosLongRadDifference + $sinLatRad * $destSinLatRad
        );
    }

    /**
     * Calculate the distance in metres to another LatLong coordinate.
     *
     * This is a relatively expensive calculation to perform. It treats the Earth as an ellipsoid of the dimensions
     * given for this LatLong's Datum's Ellipsoid so is more accurate than the Spherical law of cosines.
     *
     *
     * @throws InvalidArgumentException If the datum of this object does not match that of the $destination LatLong.
     * @return float|null The distance in metres, or null if there was a problem.
     */
    public function calculateDistanceVincenty(LatLong $destination): ?float
    {
        if ($destination->getDatum() != $this->datum) {
            throw new InvalidArgumentException('Datums must match to calculate distance.');
        }

        $ellipsoid = $this->datum->getEllipsoid();
        $a = $ellipsoid->getSemiMajorAxisMetres();
        $b = $ellipsoid->getSemiMinorAxisMetres();
        $f = $ellipsoid->getInverseFlattening();

        $longDiffRad = deg2rad($destination->getLongitude() - $this->getLongitude());

        $reducedLat = atan((1 - $f) * tan($this->getLatitudeRadians()));
        $reducedLatDest = atan((1 - $f) * tan($destination->getLatitudeRadians()));

        $sinReducedLat = sin($reducedLat);
        $cosReducedLat = cos($reducedLat);

        $sinReducedLatDest = sin($reducedLatDest);
        $cosReducedLatDest = cos($reducedLatDest);

        $lambda = $longDiffRad;
        $maxIterations = 100;

        do {
            $sinLambda = sin($lambda);
            $cosLambda = cos($lambda);

            $sinSigma = sqrt(
                ($cosReducedLatDest * $sinLambda) ** 2 +
                ($cosReducedLat * $sinReducedLatDest - $sinReducedLat * $cosReducedLatDest * $cosLambda) ** 2
            );

            // Return 0 if the points are coincident.
            if ($sinSigma === 0.0) {
                return 0.0;
            }

            $cosSigma = $sinReducedLat * $sinReducedLatDest + $cosReducedLat * $cosReducedLatDest * $cosLambda;

            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlpha = $cosReducedLat * $cosReducedLatDest * $sinLambda / $sinSigma;
            $cosSqAlpha = 1 - $sinAlpha ** 2;
            // At the equator, $cosSqAlpha === 0.
            $cos2SigmaM = $cosSigma - 2 * $sinReducedLat * $sinReducedLatDest / $cosSqAlpha;

            $c = $f / 16 * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));

            $lambdaP = $lambda;
            $lambda = $longDiffRad + (1 - $c) * $f * $sinAlpha *
                ($sigma + $c * $sinSigma * ($cos2SigmaM + $c * $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM)));
        } while (abs($lambda - $lambdaP) > '1e-12' && --$maxIterations > 0);

        if ($maxIterations === 0) {
            return null;  // Formula failed to converge.
        }

        $uSq = $cosSqAlpha * ($a * $a - $b * $b) / ($b * $b);
        $A = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
        $B = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));

        $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 * ($cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) -
            $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)));

        return $b * $A * ($sigma - $deltaSigma);
    }

    /**
     * Calculate the initial bearing in decimal degrees (forward azimuth) to follow from this point to arrive at the
     * given destination.
     *
     * @param LatLong $destination The destination.
     */
    public function calculateInitialBearing(LatLong $destination): float
    {
        $latRad = $this->getLatitudeRadians();
        $destLatRad = $destination->getLatitudeRadians();

        $diffLongRad = deg2rad($destination->getLongitude() - $this->getLongitude());

        $x = cos($latRad) * sin($destLatRad) - sin($latRad) * cos($destLatRad) * cos($diffLongRad);
        $y = sin($diffLongRad) * cos($destLatRad);

        $bearing = atan2($y, $x);

        return fmod(rad2deg($bearing) + 360, 360);
    }

    /**
     * Calculate the final bearing in decimal degrees to follow from this point to arrive at the given destination.
     */
    public function calculateFinalBearing(LatLong $destination): float
    {
        $initialBearing = $destination->calculateInitialBearing($this);

        return fmod($initialBearing + 180, 360);
    }

    /**
     * Calculate the destination point reached when the given distance is travelled along the given bearing.
     *
     * @param float $bearing The initial bearing in decimal degrees.
     * @param float $distance The distance to travel in metres.
     */
    public function calculateDestinationForBearingAndDistance(float $bearing, float $distance): LatLong
    {
        // Note, we don't use a datum-specific radius here, as we are treating the Earth as a sphere, not an ellipsoid.
        $distRad = $distance / self::EARTH_MEAN_RADIUS_METRES;

        $bearing = deg2rad($bearing);
        $latRad = $this->getLatitudeRadians();
        $longRad = $this->getLongitudeRadians();

        $sinLatRad = sin($latRad);
        $cosLatRad = cos($latRad);
        $sinDistRad = sin($distRad);
        $cosDistRad = cos($distRad);

        $destLat = asin($sinLatRad * $cosDistRad + $cosLatRad * $sinDistRad * cos($bearing));

        $destLong = $longRad + atan2(
            sin($bearing) * $sinDistRad * $cosLatRad,
            $cosDistRad - $sinLatRad * sin($destLat)
        );

        $destLong = fmod(($destLong + 3 * M_PI), (2 * M_PI)) - M_PI; // Normalise to +/-180 degrees.

        return new LatLong(rad2deg($destLat), rad2deg($destLong), $this->height, $this->datum);
    }

    public function toCartesianCoordinate(): CartesianCoordinate
    {
        $latRad = $this->getLatitudeRadians();
        $longRad = $this->getLongitudeRadians();
        $height = $this->getHeight();

        $semiMajorAxis = $this->datum->getEllipsoid()
            ->getSemiMajorAxisMetres();

        $semiMinorAxis = $this->datum->getEllipsoid()
            ->getSemiMinorAxisMetres();

        $semiMajorAxisSquared = $semiMajorAxis * $semiMajorAxis;
        $semiMinorAxisSquared = $semiMinorAxis * $semiMinorAxis;

        $sinLat = sin($latRad);
        $cosLat = cos($latRad);

        $sinLong = sin($longRad);
        $cosLong = cos($longRad);

        $ellipsoidEccentricitySquared = ($semiMajorAxisSquared - $semiMinorAxisSquared) / $semiMajorAxisSquared;

        $transverseRadiusCurvature = $semiMajorAxis / sqrt(1 - $ellipsoidEccentricitySquared * $sinLat * $sinLat);

        $x = ($transverseRadiusCurvature + $height) * $cosLat * $cosLong;
        $y = ($transverseRadiusCurvature + $height) * $cosLat * $sinLong;
        $z = ((1 - $ellipsoidEccentricitySquared) * $transverseRadiusCurvature + $height) * $sinLat;

        return new CartesianCoordinate($x, $y, $z, clone ($this->datum));
    }

    public function toLatLongInDatum(Datum $targetDatum): LatLong
    {
        $converted = null;

        if ($this->datum != $targetDatum) {
            $cartesianCoordinate = $this->toCartesianCoordinate();

            // Convert to WGS84, if we are not already in it.
            $transformToWgs84 = $this->datum->getToWgs84HelmertTransform();
            if ($transformToWgs84 instanceof HelmertTransform) {
                $cartesianCoordinate = $transformToWgs84->transform($cartesianCoordinate);
            }

            // Convert from the base datum to the target datum, if the target is not the base datum.
            $transformToTarget = $targetDatum->getFromWgs84HelmertTransform();
            if ($transformToTarget instanceof HelmertTransform) {
                $cartesianCoordinate = $transformToTarget->transform($cartesianCoordinate);
            }

            $cartesianCoordinate->setDatum($targetDatum);

            // Convert back to a LatLong.
            $converted = $cartesianCoordinate->toLatLong();
        } else {
            $converted = clone ($this);
        }

        return $converted;
    }

    public function __toString(): string
    {
        return $this->latitude . ', ' . $this->longitude;
    }
}
