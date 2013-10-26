<?php

namespace ChrisCollins\GisUtils\Test\Equation;

use ChrisCollins\GisUtils\Equation\HelmertTransform;
use ChrisCollins\GisUtils\Test\AbstractTestCase;

/**
 * HelmertTransformTest
 */
class HelmertTransformTest extends AbstractTestCase
{
    /**
     * @var HelmertTransform A HelmertTransform instance.
     */
    protected $instance = null;

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->instance = new HelmertTransform(-446.448, 125.157, -542.060, -0.1502, -0.2470, -0.8421, 20.4894);
    }

    public function testConstructorSetsExpectedPropertyValues()
    {
        $this->assertEquals(-446.448, $this->instance->getTranslationX());
        $this->assertEquals(125.157, $this->instance->getTranslationY());
        $this->assertEquals(-542.060, $this->instance->getTranslationZ());
        $this->assertEquals(-0.1502, $this->instance->getRotationX());
        $this->assertEquals(-0.2470, $this->instance->getRotationY());
        $this->assertEquals(-0.8421, $this->instance->getRotationZ());
        $this->assertEquals(20.4894, $this->instance->getScaleFactor());
    }

    /**
     * testGettersReturnValuesSetBySetters
     *
     * @param string $propertyName The name of the property.
     * @param mixed $propertyValue The value of the property.
     *
     * @dataProvider getPropertyNamesAndTestValues
     */
    public function testGettersReturnValuesSetBySetters($propertyName, $propertyValue)
    {
        $ucfirstPropertyName = ucfirst($propertyName);

        $setter = 'set' . $ucfirstPropertyName;
        $getter = 'get' . $ucfirstPropertyName;

        // Assert setters return the object.
        $object = $this->instance->$setter($propertyValue);
        $this->assertInstanceOf('ChrisCollins\GisUtils\Equation\HelmertTransform', $object);
        $this->assertEquals($this->instance, $object);

        $this->assertEquals($propertyValue, $this->instance->$getter());
    }

    /**
     * Data provider to provide test values for each property of the object.
     *
     * @return array An array, each element an array containing a property name and a test value.
     */
    public static function getPropertyNamesAndTestValues()
    {
        return array(
            array('translationX', -446.448),
            array('translationY', 125.157),
            array('translationZ', -542.060),
            array('rotationX', -0.1502),
            array('rotationY', -0.2470),
            array('rotationZ', -0.8421),
            array('scaleFactor', 20.4894)
        );
    }

    public function testGetReverseHelmertTransformNegatesEachProperty()
    {
        $reverseTransform = $this->instance->getReverseHelmertTransform();

        $this->assertEquals(-$this->instance->getTranslationX(), $reverseTransform->getTranslationX());
        $this->assertEquals(-$this->instance->getTranslationY(), $reverseTransform->getTranslationY());
        $this->assertEquals(-$this->instance->getTranslationZ(), $reverseTransform->getTranslationZ());
        $this->assertEquals(-$this->instance->getRotationX(), $reverseTransform->getRotationX());
        $this->assertEquals(-$this->instance->getRotationY(), $reverseTransform->getRotationY());
        $this->assertEquals(-$this->instance->getRotationZ(), $reverseTransform->getRotationZ());
        $this->assertEquals(-$this->instance->getScaleFactor(), $reverseTransform->getScaleFactor());
    }
}
