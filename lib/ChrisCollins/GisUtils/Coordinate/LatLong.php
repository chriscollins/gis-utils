<?php

namespace ChrisCollins\GisUtils\Coordinate;

use ChrisCollins\GisUtils\Datum\Datum;
use InvalidArgumentException;

/**
 * LatLong
 *
 * A class to represent a latitude/longitude coordinate.
 */
class LatLong
{
    /**
     * @var int The mean radius of Earth in metres.  Not datum-specific, this is used in less-accurate distance
     *          calculations, as it treats the Earth as a sphere.
     */
    private const EARTH_MEAN_RADIUS_METRES = 6371000;

    /**
     * @var float The latitude in decimal degrees.
     */
    private float $latitude;

    /**
     * @var float The longitude in decimal degrees.
     */
    private float $longitude;

    /**
     * @var float|null The height in metres.
     */
    private ?float $height;

    /**
     * @var Datum The datum.
     */
    private Datum $datum;

    /**
     * Constructor.
     *
     * @param float $latitude The latitude.
     * @param float $longitude The longitude.
     * @param float $height The height.
     * @param Datum $datum The datum.
     * @param bool $asRadians If true, the input latitude and longitude are treated as radians, not decimal degrees.
     */
    public function __construct(
        float $latitude,
        float $longitude,
        ?float $height,
        Datum $datum,
        bool $asRadians = false
    ) {
        if ($asRadians) {
            $this->setLatitudeRadians($latitude);
            $this->setLongitudeRadians($longitude);
        } else {
            $this->latitude = $latitude;
            $this->longitude = $longitude;
        }

        $this->height = $height;
        $this->datum = $datum;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Mutator method.
     *
     * @param float $latitude The new value of the property.
     *
     * @return LatLong This object.
     */
    public function setLatitude(float $latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get the latitude in radians.
     *
     * @return float The value of the property.
     */
    public function getLatitudeRadians(): float
    {
        return deg2rad($this->latitude);
    }

    /**
     * Set the latitude in radians.
     *
     * @param float $latitudeRadians The new value of the property.
     *
     * @return LatLong This object.
     */
    public function setLatitudeRadians(float $latitudeRadians)
    {
        $this->latitude = rad2deg($latitudeRadians);

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Mutator method.
     *
     * @param float $longitude The new value of the property.
     *
     * @return LatLong This object.
     */
    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the longitude in radians.
     *
     * @return float The value of the property.
     */
    public function getLongitudeRadians(): float
    {
        return deg2rad($this->longitude);
    }

    /**
     * Set the longitude in radians.
     *
     * @param float $longitudeRadians The new value of the property.
     *
     * @return LatLong This object.
     */
    public function setLongitudeRadians(float $longitudeRadians)
    {
        $this->longitude = rad2deg($longitudeRadians);

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float|null The value of the property.
     */
    public function getHeight(): ?float
    {
        return $this->height;
    }

    /**
     * Mutator method.
     *
     * @param float|null $height The new value of the property.
     *
     * @return LatLong This object.
     */
    public function setHeight(?float $height)
    {
        $this->height = $height;

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
     * @return LatLong This object.
     */
    public function setDatum(Datum $datum)
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
     * @return float The distance in metres.
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

        return $earthRadius * acos($cosLatRad * $destCostLatRad * $cosLongRadDifference + $sinLatRad * $destSinLatRad);
    }

    /**
     * Calculate the distance in metres to another LatLong coordinate.
     *
     * This is a relatively expensive calculation to perform.  It treats the Earth as an ellipsoid of the dimensions
     * given for this LatLong's Datum's Ellipsoid so is more accurate than the Spherical law of cosines.
     *
     * @param LatLong $destination The coordinate to measure the distance to.
     *
     * @return float|null The distance in metres, or null if there was a problem.
     *
     * @throws InvalidArgumentException If the datum of this object does not match that of the $destination LatLong.
     */
    public function calculateDistanceVincenty(LatLong $destination): float
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
                pow($cosReducedLatDest * $sinLambda, 2) +
                pow($cosReducedLat * $sinReducedLatDest - $sinReducedLat * $cosReducedLatDest * $cosLambda, 2)
            );

            // Return 0 if the points are coincident.
            if ($sinSigma === 0.0) {
                return 0.0;
            }

            $cosSigma = $sinReducedLat * $sinReducedLatDest + $cosReducedLat * $cosReducedLatDest * $cosLambda;

            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlpha = $cosReducedLat * $cosReducedLatDest * $sinLambda / $sinSigma;
            $cosSqAlpha = 1 - pow($sinAlpha, 2);

            $cos2SigmaM = 0;

            // At the equator, $cosSqAlpha === 0.
            if ($cosSqAlpha !== 0) {
                $cos2SigmaM = $cosSigma - 2 * $sinReducedLat * $sinReducedLatDest / $cosSqAlpha;
            }

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

        $s = $b * $A * ($sigma - $deltaSigma);

        return $s;
    }

    /**
     * Calculate the initial bearing (forward azimuth) to follow from this point to arrive at the given destination.
     *
     * @param LatLong $destination The destination.
     *
     * @return float The bearing in decimal degrees.
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
     * Calculate the final bearing to follow from this point to arrive at the given destination.
     *
     * @param LatLong $destination The destination.
     *
     * @return float The bearing in decimal degrees.
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
     *
     * @return LatLong The destination point.
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

        $destLong = fmod(($destLong + 3 * pi()), (2 * pi())) - pi(); // Normalise to +/-180 degrees.

        return new LatLong(
            rad2deg($destLat),
            rad2deg($destLong),
            $this->height,
            $this->datum
        );
    }

    /**
     * Convert this object to a CartesianCoordinate.
     *
     * @return CartesianCoordinate A CartesianCoordinate with values appropriate to this LatLong and its Datum.
     */
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

        return new CartesianCoordinate($x, $y, $z, clone($this->datum));
    }

    /**
     * Convert to a LatLong in the given datum.
     *
     * @param Datum $targetDatum The target datum.
     *
     * @return LatLong A LatLong in the given datum with equivalent coordinates set.
     */
    public function toLatLongInDatum(Datum $targetDatum): LatLong
    {
        $converted = null;

        if ($this->datum != $targetDatum) {
            $cartesianCoordinate = $this->toCartesianCoordinate();

            // Convert to WGS84, if we are not already in it.
            $transformToWgs84 = $this->datum->getToWgs84HelmertTransform();
            if ($transformToWgs84 !== null) {
                $cartesianCoordinate = $transformToWgs84->transform($cartesianCoordinate);
            }

            // Convert from the base datum to the target datum, if the target is not the base datum.
            $transformToTarget = $targetDatum->getFromWgs84HelmertTransform();
            if ($transformToTarget !== null) {
                $cartesianCoordinate = $transformToTarget->transform($cartesianCoordinate);
            }

            $cartesianCoordinate->setDatum($targetDatum);

            // Convert back to a LatLong.
            $converted = $cartesianCoordinate->toLatLong($targetDatum);
        } else {
            $converted = clone($this);
        }

        return $converted;
    }

    /**
     * Get a string representation of the object.
     *
     * @return string A string representation of the object.
     */
    public function toString(): string
    {
        return $this->latitude . ', ' . $this->longitude;
    }
}
