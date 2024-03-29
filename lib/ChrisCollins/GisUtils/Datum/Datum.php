<?php

namespace ChrisCollins\GisUtils\Datum;

use ChrisCollins\GisUtils\Equation\HelmertTransform;
use ChrisCollins\GisUtils\Ellipsoid\Ellipsoid;

/**
 * Datum
 *
 * A class to represent a Datum.
 */
class Datum
{
    /**
     * @var string The name of the datum (e.g. "WGS84").
     */
    private string $name;

    /**
     * @var Ellipsoid The ellipsoid that the datum uses.
     */
    private Ellipsoid $ellipsoid;

    /**
     * @var HelmertTransform|null A HelmertTransform to convert to this datum from WGS84,.
     */
    private ?HelmertTransform $fromWgs84HelmertTransform;

    /**
     * Constructor.
     *
     * @param string $name The name of the datum (e.g. "WGS84").
     * @param Ellipsoid $ellipsoid The ellipsoid that the datum uses.
     * @param HelmertTransform|null $fromWgs84HelmertTransform A HelmertTransform to convert to this datum from WGS84.
     */
    public function __construct($name, Ellipsoid $ellipsoid, ?HelmertTransform $fromWgs84HelmertTransform = null)
    {
        $this->name = $name;
        $this->ellipsoid = $ellipsoid;
        $this->fromWgs84HelmertTransform = $fromWgs84HelmertTransform;
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
     * @return Ellipsoid The value of the property.
     */
    public function getEllipsoid(): Ellipsoid
    {
        return $this->ellipsoid;
    }

    /**
     * Mutator method.
     *
     * @param Ellipsoid $ellipsoid The new value of the property.
     *
     * @return static This object.
     */
    public function setEllipsoid(Ellipsoid $ellipsoid): self
    {
        $this->ellipsoid = $ellipsoid;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return HelmertTransform|null The value of the property.
     */
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

        if ($this->fromWgs84HelmertTransform !== null) {
            $transform = $this->fromWgs84HelmertTransform->getReverseHelmertTransform();
        }

        return $transform;
    }

    /**
     * Mutator method.
     *
     * @param HelmertTransform $fromWgs84HelmertTransform The new value of the property.
     *
     * @return static This object.
     */
    public function setFromWgs84HelmertTransform(HelmertTransform $fromWgs84HelmertTransform): self
    {
        $this->fromWgs84HelmertTransform = $fromWgs84HelmertTransform;

        return $this;
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
