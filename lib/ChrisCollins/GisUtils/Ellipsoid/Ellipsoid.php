<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Ellipsoid;

use Stringable;

/**
 * Ellipsoid
 *
 * A class to represent an ellipsoid.
 */
class Ellipsoid implements Stringable
{
    /**
     * @param string $name The name of the ellipsoid (e.g. "WGS84").
     * @param float $semiMajorAxisMetres The length of the Earth's semi-major axis a, AKA equatorial radius in metres.
     * @param float $semiMinorAxisMetres The length of the Earth's semi-minor axis b, AKA polar radius in metres.
     * @param float $flattening The flattening.
     */
    public function __construct(
        private string $name,
        private float $semiMajorAxisMetres,
        private float $semiMinorAxisMetres,
        private float $flattening
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSemiMajorAxisMetres(): float
    {
        return $this->semiMajorAxisMetres;
    }

    public function setSemiMajorAxisMetres(float $semiMajorAxisMetres): self
    {
        $this->semiMajorAxisMetres = $semiMajorAxisMetres;

        return $this;
    }

    public function getSemiMinorAxisMetres(): float
    {
        return $this->semiMinorAxisMetres;
    }

    public function setSemiMinorAxisMetres(float $semiMinorAxisMetres): self
    {
        $this->semiMinorAxisMetres = $semiMinorAxisMetres;

        return $this;
    }

    public function getFlattening(): float
    {
        return $this->flattening;
    }

    public function setFlattening(float $flattening): self
    {
        $this->flattening = $flattening;

        return $this;
    }

    /**
     * Get the inverse flattening for the Ellipsoid.
     */
    public function getInverseFlattening(): float
    {
        return 1 / $this->flattening;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
