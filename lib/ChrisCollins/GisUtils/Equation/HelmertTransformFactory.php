<?php

namespace ChrisCollins\GisUtils\Equation;

use ChrisCollins\GisUtils\AbstractFactory;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use InvalidArgumentException;

/**
 * HelmertTransformFactory
 *
 * Class for creating HelmertTransform instances for transforming points across datums.
 */
class HelmertTransformFactory extends AbstractFactory
{
    /**
     * @var string The name of the base datum.
     */
    public const BASE_DATUM = DatumFactory::DATUM_WGS84;

    /**
     * @var array Array of data for transforming coordinates from WGS84 to the given datum.
     */
    protected static $data = [
        DatumFactory::DATUM_OSGB36 => [
            'translationX' => -446.448,
            'translationY' => 125.157,
            'translationZ' => -542.060,
            'rotationX' => -0.1502,
            'rotationY' => -0.2470,
            'rotationZ' => -0.8421,
            'scaleFactor' => 20.4894
        ],
        DatumFactory::DATUM_ED50 => [
            'translationX' => 89.5,
            'translationY' => 93.8,
            'translationZ' => 123.1,
            'rotationX' => 0,
            'rotationY' => 0,
            'rotationZ' => 0.156,
            'scaleFactor' => -1.2
        ]
    ];

    /**
     * Get a HelmertTransform for transforming base datum coordinates to coordinates in a given datum.
     *
     * @param string $datum The name of the datum to transform to.
     *
     * @return HelmertTransform A HelmertTransform to convert one datum to another.
     *
     * @throws InvalidArgumentException If the datum is not supported.
     */
    public function createTransformFromBaseToDatum($datum)
    {
        return $this->create($datum);
    }

    /**
     * Get a HelmertTransform for transforming coordinates in a given datum to the base datum coordinates.
     *
     * @param string $datum The name of the datum to transform from.
     *
     * @return HelmertTransform A HelmertTransform to convert one datum to another.
     *
     * @throws InvalidArgumentException If the datum is not supported.
     */
    public function createTransformFromDatumToBase($datum)
    {
        $helmertTransform = $this->create($datum);

        return $helmertTransform->getReverseHelmertTransform();
    }

    /**
     * {@inheritdoc}
     */
    protected function createFromData($name, array $data)
    {
        return new HelmertTransform(
            $data['translationX'],
            $data['translationY'],
            $data['translationZ'],
            $data['rotationX'],
            $data['rotationY'],
            $data['rotationZ'],
            $data['scaleFactor']
        );
    }
}
