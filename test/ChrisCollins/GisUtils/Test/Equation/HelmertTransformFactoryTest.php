<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Equation;

use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransform;
use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;

/**
 * HelmertTransformFactoryTest
 */
final class HelmertTransformFactoryTest extends AbstractTestCase
{
    /** @var HelmertTransformFactory A HelmertTransformFactory instance. */
    private $instance;

    protected function setUp(): void
    {
        $this->instance = new HelmertTransformFactory();
    }

    #[Test]
    public function createTransformFromBaseToDatumReturnsExpectedHelmertTransform(): void
    {
        $expected = $this->getOSGB36ToBaseHelmertTransform();
        $actual = $this->instance->createTransformFromBaseToDatum(DatumFactory::DATUM_OSGB36);

        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function createTransformFromDatumToBaseReturnsExpectedHelmertTransform(): void
    {
        $expected = $this->getOSGB36ToBaseHelmertTransform()
            ->getReverseHelmertTransform();

        $actual = $this->instance->createTransformFromDatumToBase(DatumFactory::DATUM_OSGB36);

        $this->assertEquals($expected, $actual);
    }

    #[Test]
    public function createTransformFromBaseToDatumThrowsExceptionForUnknownDatum(): void
    {
        $exceptionThrown = false;

        try {
            $this->instance->createTransformFromBaseToDatum('Nonexistant datum');
        } catch (InvalidArgumentException) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    #[Test]
    public function createTransformFromDatumToBaseThrowsExceptionForUnknownDatum(): void
    {
        $exceptionThrown = false;

        try {
            $this->instance->createTransformFromDatumToBase('Nonexistant datum');
        } catch (InvalidArgumentException) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    /**
     * Get a HelmertTransform to transform OSGB36 coordinates to the base datum.
     *
     * @return HelmertTransform The HelmertTransform.
     */
    protected function getOSGB36ToBaseHelmertTransform()
    {
        return new HelmertTransform(-446.448, 125.157, -542.060, -0.1502, -0.2470, -0.8421, 20.4894);
    }
}
