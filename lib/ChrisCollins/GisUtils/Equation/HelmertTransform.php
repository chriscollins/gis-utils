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
    private $translationX;

    /**
     * @var float The y translation value in metres.
     */
    private $translationY;

    /**
     * @var float The z translation value in metres.
     */
    private $translationZ;

    /**
     * @var float The x rotation value in arcseconds.
     */
    private $rotationX;

    /**
     * @var float The y rotation value in arcseconds.
     */
    private $rotationY;

    /**
     * @var float The z rotation value in arcseconds.
     */
    private $rotationZ;

    /**
     * @var float The scale factor value in parts-per-million.
     */
    private $scaleFactor;

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
        $translationX,
        $translationY,
        $translationZ,
        $rotationX,
        $rotationY,
        $rotationZ,
        $scaleFactor
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
    public function transform(CartesianCoordinate $coordinate)
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
     * @return HelmertTransform The reverse of this transform.
     */
    public function getReverseHelmertTransform()
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
    public function getTranslationX()
    {
        return $this->translationX;
    }

    /**
     * Mutator method.
     *
     * @param float $translationX The new value of the property.
     *
     * @return HelmertTransform This object.
     */
    public function setTranslationX($translationX)
    {
        $this->translationX = $translationX;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getTranslationY()
    {
        return $this->translationY;
    }

    /**
     * Mutator method.
     *
     * @param float $translationY The new value of the property.
     *
     * @return HelmertTransform This object.
     */
    public function setTranslationY($translationY)
    {
        $this->translationY = $translationY;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getTranslationZ()
    {
        return $this->translationZ;
    }

    /**
     * Mutator method.
     *
     * @param float $translationZ The new value of the property.
     *
     * @return HelmertTransform This object.
     */
    public function setTranslationZ($translationZ)
    {
        $this->translationZ = $translationZ;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getRotationX()
    {
        return $this->rotationX;
    }

    /**
     * Get the x rotation in radians.
     *
     * @return float The value of the property.
     */
    public function getRotationXRadians()
    {
        return deg2rad($this->rotationX / self::ARCSECONDS_PER_DEGREE);
    }

    /**
     * Mutator method.
     *
     * @param float $rotationX The new value of the property.
     *
     * @return HelmertTransform This object.
     */
    public function setRotationX($rotationX)
    {
        $this->rotationX = $rotationX;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getRotationY()
    {
        return $this->rotationY;
    }

    /**
     * Get the y rotation in radians.
     *
     * @return float The value of the property.
     */
    public function getRotationYRadians()
    {
        return deg2rad($this->rotationY / self::ARCSECONDS_PER_DEGREE);
    }

    /**
     * Mutator method.
     *
     * @param float $rotationY The new value of the property.
     *
     * @return HelmertTransform This object.
     */
    public function setRotationY($rotationY)
    {
        $this->rotationY = $rotationY;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getRotationZ()
    {
        return $this->rotationZ;
    }

    /**
     * Get the z rotation in radians.
     *
     * @return float The value of the property.
     */
    public function getRotationZRadians()
    {
        return deg2rad($this->rotationZ / self::ARCSECONDS_PER_DEGREE);
    }

    /**
     * Mutator method.
     *
     * @param float $rotationZ The new value of the property.
     *
     * @return HelmertTransform This object.
     */
    public function setRotationZ($rotationZ)
    {
        $this->rotationZ = $rotationZ;

        return $this;
    }

    /**
     * Accessor method.
     *
     * @return float The value of the property.
     */
    public function getScaleFactor()
    {
        return $this->scaleFactor;
    }

    /**
     * Mutator method.
     *
     * @param float $scaleFactor The new value of the property.
     *
     * @return HelmertTransform This object.
     */
    public function setScaleFactor($scaleFactor)
    {
        $this->scaleFactor = $scaleFactor;

        return $this;
    }
}
