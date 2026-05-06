<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Datum;

use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;
use ChrisCollins\GisUtils\Equation\HelmertTransform;
use Stringable;

/**
 * Datum
 *
 * A class to represent a Datum.
 */
class Datum implements Stringable
{
    /**
     * Constructor.
     *
     * @param string $name The name of the datum (e.g. "WGS84").
     * @param Ellipsoid $ellipsoid The ellipsoid that the datum uses.
     * @param HelmertTransform|null $fromWgs84HelmertTransform A HelmertTransform to convert to this datum from WGS84.
     */
    public function __construct(
        private string $name,
        private Ellipsoid $ellipsoid,
        private ?HelmertTransform $fromWgs84HelmertTransform = null
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

    public function getEllipsoid(): Ellipsoid
    {
        return $this->ellipsoid;
    }

    public function setEllipsoid(Ellipsoid $ellipsoid): self
    {
        $this->ellipsoid = $ellipsoid;

        return $this;
    }

    public function getFromWgs84HelmertTransform(): ?HelmertTransform
    {
        return $this->fromWgs84HelmertTransform;
    }

    /**
     * Get a HelmertTransform to convert to WGS84 from this datum.
     *
     * @return HelmertTransform|null The HelmertTransform, or null if it could not be determined.
     */
    public function getToWgs84HelmertTransform(): ?HelmertTransform
    {
        $transform = null;

        if ($this->fromWgs84HelmertTransform instanceof HelmertTransform) {
            $transform = $this->fromWgs84HelmertTransform->getReverseHelmertTransform();
        }

        return $transform;
    }

    public function setFromWgs84HelmertTransform(HelmertTransform $fromWgs84HelmertTransform): self
    {
        $this->fromWgs84HelmertTransform = $fromWgs84HelmertTransform;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
