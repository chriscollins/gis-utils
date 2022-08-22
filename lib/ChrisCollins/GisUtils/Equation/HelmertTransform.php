<?php

namespace ChrisCollins\GisUtils\Equation;

use ChrisCollins\GisUtils\Coordinate\CartesianCoordinate;

/**
 * HelmertTransform
 *
 * A class to represent a Helmert transformation: A transformation used in converting between datums.
 */
class HelmertTransform
{
    /**
     * @var int Constant for the number of arcseconds in a degree.
     */
    private const ARCSECONDS_PER_DEGREE = 3600;

    /**
     * @var float The x translation value in metres.
     */
    private float $translationX;

    /**
     * @var float The y translation value in metres.
     */
    private float $translationY;

    /**
     * @var float The z translation value in metres.
     */
    private float $translationZ;

    /**
     * @var float The x rotation value in arcseconds.
     */
    private float $rotationX;

    /**
     * @var float The y rotation value in arcseconds.
     */
    private float $rotationY;

    /**
     * @var float The z rotation value in arcseconds.
     */
    private float $rotationZ;

    /**
     * @var float The scale factor value in parts-per-million.
     */
    private float $scaleFactor;

    /**
     * Constructor.
     *
     * @param float $translationX The x translation value in metres.
     * @param float $translationY The y translation value in metres.
     * @param float $translationZ The z translation value in metres.
     * @param float $rotationX The x rotation value in arcseconds.
     * @param float $rotationY The y rotation value in arcseconds.
     * @param float $rotationZ The z rotation value in arcseconds.
     * @param float $scaleFactor The scale factor value in parts-per-million.
     */
    public function __construct(
        float $translationX,
        float $translationY,
        float $translationZ,
        float $rotationX,
        float $rotationY,
        float $rotationZ,
        float $scaleFactor
    ) {
        $this->translationX = $translationX;
        $this->translationY = $translationY;
        $this->translationZ = $translationZ;
        $this->rotationX = $rotationX;
        $this->rotationY = $rotationY;
        $this->rotationZ = $rotationZ;
        $this->scaleFactor = $scaleFactor;
    }

    /**
     * Perform the HelmertTransform on a CartesianCoordinate.
     *
     * @param CartesianCoordinate $coordinate The CartesianCoordinate to transform.
     *
     * @return CartesianCoordinate The transformed CartesianCoordinate.
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

        return new CartesianCoordinate($x2, $y2, $z2, clone($coordinate->getDatum()));
    }

    /**
     * Get the reverse of this transform, useful for performing transforms in the other direction.
     *
     * @return static The reverse of this transform.
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

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getTranslationX(): float
    {
        return $this->translationX;
    }

    /**
     * Mutator method.
     *
     * @param float $translationX The new value of the property.
     *
     * @return static This object.
     */
    public function setTranslationX(float $translationX): self
    {
        $this->translationX = $translationX;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getTranslationY(): float
    {
        return $this->translationY;
    }

    /**
     * Mutator method.
     *
     * @param float $translationY The new value of the property.
     *
     * @return static This object.
     */
    public function setTranslationY(float $translationY): self
    {
        $this->translationY = $translationY;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getTranslationZ(): float
    {
        return $this->translationZ;
    }

    /**
     * Mutator method.
     *
     * @param float $translationZ The new value of the property.
     *
     * @return static This object.
     */
    public function setTranslationZ(float $translationZ): self
    {
        $this->translationZ = $translationZ;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getRotationX(): float
    {
        return $this->rotationX;
    }

    /**
     * Get the x rotation in radians.
     *
     * @return float The value of the property.
     */
    public function getRotationXRadians(): float
    {
        return deg2rad($this->rotationX / self::ARCSECONDS_PER_DEGREE);
    }

    /**
     * Mutator method.
     *
     * @param float $rotationX The new value of the property.
     *
     * @return static This object.
     */
    public function setRotationX(float $rotationX): self
    {
        $this->rotationX = $rotationX;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getRotationY(): float
    {
        return $this->rotationY;
    }

    /**
     * Get the y rotation in radians.
     *
     * @return float The value of the property.
     */
    public function getRotationYRadians(): float
    {
        return deg2rad($this->rotationY / self::ARCSECONDS_PER_DEGREE);
    }

    /**
     * Mutator method.
     *
     * @param float $rotationY The new value of the property.
     *
     * @return static This object.
     */
    public function setRotationY(float $rotationY): self
    {
        $this->rotationY = $rotationY;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getRotationZ(): float
    {
        return $this->rotationZ;
    }

    /**
     * Get the z rotation in radians.
     *
     * @return float The value of the property.
     */
    public function getRotationZRadians(): float
    {
        return deg2rad($this->rotationZ / self::ARCSECONDS_PER_DEGREE);
    }

    /**
     * Mutator method.
     *
     * @param float $rotationZ The new value of the property.
     *
     * @return static This object.
     */
    public function setRotationZ($rotationZ): self
    {
        $this->rotationZ = $rotationZ;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getScaleFactor(): float
    {
        return $this->scaleFactor;
    }

    /**
     * Mutator method.
     *
     * @param float $scaleFactor The new value of the property.
     *
     * @return static This object.
     */
    public function setScaleFactor(float $scaleFactor): self
    {
        $this->scaleFactor = $scaleFactor;

        return $this;
    }
}
