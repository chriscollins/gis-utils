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
    private string $name;

    /**
     * @var float The length of the Earth's semi-major axis a, AKA equatorial radius in metres.
     */
    private float $semiMajorAxisMetres;

    /**
     * @var float The length of the Earth's semi-minor axis b, AKA polar radius in metres.
     */
    private float $semiMinorAxisMetres;

    /**
     * @var float The flattening.
     */
    private float $flattening;

    /**
     * Constructor.
     *
     * @param string $name The name of the ellipsoid (e.g. "WGS84").
     * @param float $semiMajorAxisMetres The length of the Earth's semi-major axis a, AKA equatorial radius in metres.
     * @param float $semiMinorAxisMetres The length of the Earth's semi-minor axis b, AKA polar radius in metres.
     * @param float $flattening The flattening.
     */
    public function __construct(string $name, float $semiMajorAxisMetres, float $semiMinorAxisMetres, float $flattening)
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Mutator method.
     *
     * @param string $name The new value of the property.
     *
     * @return static This object.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getSemiMajorAxisMetres(): float
    {
        return $this->semiMajorAxisMetres;
    }

    /**
     * Mutator method.
     *
     * @param float $semiMajorAxisMetres The new value of the property.
     *
     * @return static This object.
     */
    public function setSemiMajorAxisMetres(float $semiMajorAxisMetres): self
    {
        $this->semiMajorAxisMetres = $semiMajorAxisMetres;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getSemiMinorAxisMetres(): float
    {
        return $this->semiMinorAxisMetres;
    }

    /**
     * Mutator method.
     *
     * @param float $semiMinorAxisMetres The new value of the property.
     *
     * @return static This object.
     */
    public function setSemiMinorAxisMetres(float $semiMinorAxisMetres): self
    {
        $this->semiMinorAxisMetres = $semiMinorAxisMetres;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getFlattening(): float
    {
        return $this->flattening;
    }

    /**
     * Mutator method.
     *
     * @param float $flattening The new value of the property.
     *
     * @return static This object.
     */
    public function setFlattening(float $flattening): self
    {
        $this->flattening = $flattening;

        return $this;
    }

    /**
     * Get the inverse flattening for the Ellipsoid.
     *
     * @return float The inverse flattening.
     */
    public function getInverseFlattening(): float
    {
        return 1 / $this->flattening;
    }

    /**
     * Get a string representation of the object.
     *
     * @return string A string representation of the object.
     */
    public function toString(): string
    {
        return $this->name;
    }
}
