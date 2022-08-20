<?php

namespace ChrisCollins\GisUtils\Test\Equation;

use ChrisCollins\GisUtils\Equation\HelmertTransformFactory;
use ChrisCollins\GisUtils\Equation\HelmertTransform;
use ChrisCollins\GisUtils\Datum\DatumFactory;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use \InvalidArgumentException;

/**
 * HelmertTransformFactoryTest
 */
class HelmertTransformFactoryTest extends AbstractTestCase
{
    /**
     * @var HelmertTransformFactory A HelmertTransformFactory instance.
     */
    private $instance;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        $this->instance = new HelmertTransformFactory();
    }

    public function testCreateTransformFromBaseToDatumReturnsExpectedHelmertTransform()
    {
        $expected = $this->getOSGB36ToBaseHelmertTransform();
        $actual = $this->instance->createTransformFromBaseToDatum(DatumFactory::DATUM_OSGB36);

        $this->assertEquals($expected, $actual);
    }

    public function testCreateTransformFromDatumToBaseReturnsExpectedHelmertTransform()
    {
        $expected = $this->getOSGB36ToBaseHelmertTransform()
            ->getReverseHelmertTransform();

        $actual = $this->instance->createTransformFromDatumToBase(DatumFactory::DATUM_OSGB36);

        $this->assertEquals($expected, $actual);
    }

    public function testCreateTransformFromBaseToDatumThrowsExceptionForUnknownDatum()
    {
        $exceptionThrown = false;

        try {
            $this->instance->createTransformFromBaseToDatum('Nonexistant datum');
        } catch (InvalidArgumentException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testCreateTransformFromDatumToBaseThrowsExceptionForUnknownDatum()
    {
        $exceptionThrown = false;

        try {
            $this->instance->createTransformFromDatumToBase('Nonexistant datum');
        } catch (InvalidArgumentException $e) {
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
