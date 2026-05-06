<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Datum;

use ChrisCollins\GisUtils\AbstractFactory;
use ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use InvalidArgumentException;

/**
 * DatumFactory
 *
 * A class for creating datums.
 *
 * @extends AbstractFactory<Datum>
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

    /** @var array<string,array<string,string>> Array of configuration for all supported datums, keyed on their names. */
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

    public function __construct(
        private readonly EllipsoidFactory $ellipsoidFactory,
        private readonly HelmertTransformFactory $helmertTransformFactory
    ) {
    }

    /**
     * @param array<string,string> $data
     */
    protected function createFromData(string $name, array $data): Datum
    {
        $ellipsoid = $this->ellipsoidFactory->create($data['ellipsoid']);

        try {
            $helmertTransform = $this->helmertTransformFactory->create($name);
        } catch (InvalidArgumentException) {
            $helmertTransform = null;
        }

        return new Datum($name, $ellipsoid, $helmertTransform);
    }

    public function createDefault(): Datum
    {
        return $this->create(self::DATUM_WGS84);
    }
}
