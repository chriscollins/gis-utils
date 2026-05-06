<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Equation;

use ChrisCollins\GisUtils\Coordinate\CartesianCoordinate;

/**
 * HelmertTransform
 *
 * A class to represent a Helmert transformation: A transformation used in converting between datums.
 */
class HelmertTransform
{
    /** @var int Constant for the number of arcseconds in a degree. */
    private const ARCSECONDS_PER_DEGREE = 3600;

    /**
     * @param float $translationX The x translation value in metres.
     * @param float $translationY The y translation value in metres.
     * @param float $translationZ The z translation value in metres.
     * @param float $rotationX The x rotation value in arcseconds.
     * @param float $rotationY The y rotation value in arcseconds.
     * @param float $rotationZ The z rotation value in arcseconds.
     * @param float $scaleFactor The scale factor value in parts-per-million.
     */
    public function __construct(
        private float $translationX,
        private float $translationY,
        private float $translationZ,
        private float $rotationX,
        private float $rotationY,
        private float $rotationZ,
        private float $scaleFactor
    ) {
    }

    /**
     * Perform the HelmertTransform on a CartesianCoordinate.
     */
    public function transform(CartesianCoordinate $coordinate): CartesianCoordinate
    {
        $x = $coordinate->getX();
        $y = $coordinate->getY();
        $z = $coordinate->getZ();

        $tx = $this->translationX;
        $ty = $this->translationY;
        $tz = $this->translationZ;

        $rx = $this->getRotationXRadians();
        $ry = $this->getRotationYRadians();
        $rz = $this->getRotationZRadians();

        // Normalise parts-per-million to (scaleFactor + 1).
        $s1 = $this->scaleFactor / '1e6' + 1;

        // Apply the transform.
        $x2 = $tx + $x * $s1 - $y * $rz + $z * $ry;
        $y2 = $ty + $x * $rz + $y * $s1 - $z * $rx;
        $z2 = $tz - $x * $ry + $y * $rx + $z * $s1;

        return new CartesianCoordinate($x2, $y2, $z2, clone ($coordinate->getDatum()));
    }

    /**
     * Get the reverse of this transform: useful for performing transforms in the other direction.
     */
    public function getReverseHelmertTransform(): self
    {
        return new HelmertTransform(
            -$this->translationX,
            -$this->translationY,
            -$this->translationZ,
            -$this->rotationX,
            -$this->rotationY,
            -$this->rotationZ,
            -$this->scaleFactor
        );
    }

    public function getTranslationX(): float
    {
        return $this->translationX;
    }

    public function setTranslationX(float $translationX): self
    {
        $this->translationX = $translationX;

        return $this;
    }

    public function getTranslationY(): float
    {
        return $this->translationY;
    }

    public function setTranslationY(float $translationY): self
    {
        $this->translationY = $translationY;

        return $this;
    }

    public function getTranslationZ(): float
    {
        return $this->translationZ;
    }

    public function setTranslationZ(float $translationZ): self
    {
        $this->translationZ = $translationZ;

        return $this;
    }

    public function getRotationX(): float
    {
        return $this->rotationX;
    }

    public function getRotationXRadians(): float
    {
        return deg2rad($this->rotationX / self::ARCSECONDS_PER_DEGREE);
    }

    public function setRotationX(float $rotationX): self
    {
        $this->rotationX = $rotationX;

        return $this;
    }

    public function getRotationY(): float
    {
        return $this->rotationY;
    }

    public function getRotationYRadians(): float
    {
        return deg2rad($this->rotationY / self::ARCSECONDS_PER_DEGREE);
    }

    public function setRotationY(float $rotationY): self
    {
        $this->rotationY = $rotationY;

        return $this;
    }

    public function getRotationZ(): float
    {
        return $this->rotationZ;
    }

    public function getRotationZRadians(): float
    {
        return deg2rad($this->rotationZ / self::ARCSECONDS_PER_DEGREE);
    }

    public function setRotationZ(float $rotationZ): self
    {
        $this->rotationZ = $rotationZ;

        return $this;
    }

    public function getScaleFactor(): float
    {
        return $this->scaleFactor;
    }

    public function setScaleFactor(float $scaleFactor): self
    {
        $this->scaleFactor = $scaleFactor;

        return $this;
    }
}
