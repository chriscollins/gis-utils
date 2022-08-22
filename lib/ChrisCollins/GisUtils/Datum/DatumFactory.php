<?php

namespace ChrisCollins\GisUtils\Datum;

use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\AbstractFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use InvalidArgumentException;

/**
 * DatumFactory
 *
 * A class for creating datums.
 */
class DatumFactory extends AbstractFactory
{
    /**#@+
     * @var string Constant for the name of the datum.
     */
    public const DATUM_WGS84 = 'WGS84';
    public const DATUM_OSGB36 = 'OSGB36';
    public const DATUM_ED50 = 'ED50';
    /**#@-*/

    /**
     * @var array Array of configuration for all supported datums, keyed on their names.
     */
    protected static $data = [
        self::DATUM_WGS84 => [
            'ellipsoid' => EllipsoidFactory::ELLIPSOID_WGS84
        ],
        self::DATUM_OSGB36 => [
            'ellipsoid' => EllipsoidFactory::ELLIPSOID_AIRY_1830
        ],
        self::DATUM_ED50 => [
            'ellipsoid' => EllipsoidFactory::ELLIPSOID_INTERNATIONAL_1924
        ]
    ];

    /**
     * @var EllipsoidFactory The EllipsoidFactory.
     */
    private $ellipsoidFactory = null;

    /**
     * @var HelmertTransformFactory The HelmertTransformFactory.
     */
    private $helmertTransformFactory = null;

    /**
     * Constructor.
     *
     * @param EllipsoidFactory $ellipsoidFactory The EllipsoidFactory.
     * @param HelmertTransformFactory $helmertTransformFactory The HelmertTransformFactory.
     */
    public function __construct(EllipsoidFactory $ellipsoidFactory, HelmertTransformFactory $helmertTransformFactory)
    {
        $this->ellipsoidFactory = $ellipsoidFactory;
        $this->helmertTransformFactory = $helmertTransformFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function createFromData(string $name, array $data): Datum
    {
        $ellipsoid = $this->ellipsoidFactory->create($data['ellipsoid']);

        try {
            $helmertTransform = $this->helmertTransformFactory->create($name);
        } catch (InvalidArgumentException $e) {
            $helmertTransform = null;
        }

        return new Datum($name, $ellipsoid, $helmertTransform);
    }

    /**
     * Factory method to create the default datum.
     *
     * @return Datum A Datum.
     */
    public function createDefault(): Datum
    {
        return $this->create(self::DATUM_WGS84);
    }
}
