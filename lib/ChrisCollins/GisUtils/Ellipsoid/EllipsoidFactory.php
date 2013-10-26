<?php

namespace ChrisCollins\GisUtils\Ellipsoid;

use ChrisCollins\GisUtils\AbstractFactory;
use \InvalidArgumentException;

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
    const ELLIPSOID_WGS84 = 'WGS84';
    const ELLIPSOID_AIRY_1830 = 'AIRY_1830';
    const ELLIPSOID_INTERNATIONAL_1924 = 'INTERNATIONAL_1924';
    /**#@-*/

    /**
     * @var array Array of configuration for all supported ellipsoids, keyed on their names.
     */
    protected static $data = array(
        self::ELLIPSOID_WGS84 => array(
            'semiMajorAxisMetres' => 6378137,
            'semiMinorAxisMetres' => 6356752.314140,
            'flattening' => 298.257223563
        ),
        self::ELLIPSOID_AIRY_1830 => array(
            'semiMajorAxisMetres' => 6377563.396,
            'semiMinorAxisMetres' => 6356256.910,
            'flattening' => 299.3249646
        ),
        self::ELLIPSOID_INTERNATIONAL_1924 => array(
            'semiMajorAxisMetres' => 6378388,
            'semiMinorAxisMetres' => 6356911.9,
            'flattening' => 297
        )
    );

    /**
     * {@inheritdoc}
     */
    protected function createFromData($name, array $data)
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
    public function createDefault()
    {
        return $this->create(self::ELLIPSOID_WGS84);
    }
}
