<?php

namespace ChrisCollins\GisUtils\Ellipsoid;

use ChrisCollins\GisUtils\AbstractFactory;
use InvalidArgumentException;

/**
 * EllipsoidFactory
 *
 * A class for creating ellipsoids.
 */
class EllipsoidFactory extends AbstractFactory
{
    /**#@+
     * @var string Constant for the name of the ellipsoid.
     */
    public const ELLIPSOID_WGS84 = 'WGS84';
    public const ELLIPSOID_AIRY_1830 = 'AIRY_1830';
    public const ELLIPSOID_INTERNATIONAL_1924 = 'INTERNATIONAL_1924';
    /**#@-*/

    /**
     * @var array Array of configuration for all supported ellipsoids, keyed on their names.
     */
    protected static $data = [
        self::ELLIPSOID_WGS84 => [
            'semiMajorAxisMetres' => 6378137,
            'semiMinorAxisMetres' => 6356752.314140,
            'flattening' => 298.257223563
        ],
        self::ELLIPSOID_AIRY_1830 => [
            'semiMajorAxisMetres' => 6377563.396,
            'semiMinorAxisMetres' => 6356256.910,
            'flattening' => 299.3249646
        ],
        self::ELLIPSOID_INTERNATIONAL_1924 => [
            'semiMajorAxisMetres' => 6378388,
            'semiMinorAxisMetres' => 6356911.9,
            'flattening' => 297
        ]
    ];

    /**
     * {@inheritdoc}
     */
    protected function createFromData(string $name, array $data): Ellipsoid
    {
        return new Ellipsoid(
            $name,
            $data['semiMajorAxisMetres'],
            $data['semiMinorAxisMetres'],
            $data['flattening']
        );
    }

    /**
     * Factory method to create the default ellipsoid.
     *
     * @return Ellipsoid An Ellipsoid.
     */
    public function createDefault(): Ellipsoid
    {
        return $this->create(self::ELLIPSOID_WGS84);
    }
}
