<?php

namespace ChrisCollins\GisUtils\Ellipsoid;

/**
 * Ellipsoid
 *
 * A class to represent an ellipsoid.
 */
class Ellipsoid
{
    /**
     * @var string The name of the ellipsoid (e.g. "WGS84").
     */
    private $name;

    /**
     * @var float The length of the Earth's semi-major axis a, AKA equatorial radius in metres.
     */
    private $semiMajorAxisMetres;

    /**
     * @var float The length of the Earth's semi-minor axis b, AKA polar radius in metres.
     */
    private $semiMinorAxisMetres;

    /**
     * @var float The flattening.
     */
    private $flattening;

    /**
     * Constructor.
     *
     * @param string $name The name of the ellipsoid (e.g. "WGS84").
     * @param float $semiMajorAxisMetres The length of the Earth's semi-major axis a, AKA equatorial radius in metres.
     * @param float $semiMinorAxisMetres The length of the Earth's semi-minor axis b, AKA polar radius in metres.
     * @param float $flattening The flattening.
     */
    public function __construct($name, $semiMajorAxisMetres, $semiMinorAxisMetres, $flattening)
    {
        $this->name = $name;
        $this->semiMajorAxisMetres = $semiMajorAxisMetres;
        $this->semiMinorAxisMetres = $semiMinorAxisMetres;
        $this->flattening = $flattening;
    }

    /**
     * Accessor method.
     *
     * @return string The value of the property.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Mutator method.
     *
     * @param string $name The new value of the property.
     *
     * @return Ellipsoid This object.
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getSemiMajorAxisMetres()
    {
        return $this->semiMajorAxisMetres;
    }

    /**
     * Mutator method.
     *
     * @param float $semiMajorAxisMetres The new value of the property.
     *
     * @return Ellipsoid This object.
     */
    public function setSemiMajorAxisMetres($semiMajorAxisMetres)
    {
        $this->semiMajorAxisMetres = $semiMajorAxisMetres;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getSemiMinorAxisMetres()
    {
        return $this->semiMinorAxisMetres;
    }

    /**
     * Mutator method.
     *
     * @param float $semiMinorAxisMetres The new value of the property.
     *
     * @return Ellipsoid This object.
     */
    public function setSemiMinorAxisMetres($semiMinorAxisMetres)
    {
        $this->semiMinorAxisMetres = $semiMinorAxisMetres;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getFlattening()
    {
        return $this->flattening;
    }

    /**
     * Mutator method.
     *
     * @param float $flattening The new value of the property.
     *
     * @return Ellipsoid This object.
     */
    public function setFlattening($flattening)
    {
        $this->flattening = $flattening;

        return $this;
    }

    /**
     * Get the inverse flattening for the Ellipsoid.
     *
     * @return float The inverse flattening.
     */
    public function getInverseFlattening()
    {
        return 1 / $this->flattening;
    }

    /**
     * Get a string representation of the object.
     *
     * @return string A string representation of the object.
     */
    public function toString()
    {
        return $this->name;
    }
}
