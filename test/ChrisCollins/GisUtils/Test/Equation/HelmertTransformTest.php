<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Equation;

use ChrisCollins\GisUtils\Equation\HelmertTransform;
use ChrisCollins\GisUtils\Test\AbstractTestCase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * HelmertTransformTest
 */
final class HelmertTransformTest extends AbstractTestCase
{
    /** @var HelmertTransform A HelmertTransform instance. */
    private $instance;

    protected function setUp(): void
    {
        $this->instance = new HelmertTransform(-446.448, 125.157, -542.060, -0.1502, -0.2470, -0.8421, 20.4894);
    }

    #[Test]
    public function constructorSetsExpectedPropertyValues(): void
    {
        $this->assertEquals(-446.448, $this->instance->getTranslationX());
        $this->assertEqualsWithDelta(125.157, $this->instance->getTranslationY(), PHP_FLOAT_EPSILON);
        $this->assertEquals(-542.060, $this->instance->getTranslationZ());
        $this->assertEquals(-0.1502, $this->instance->getRotationX());
        $this->assertEquals(-0.2470, $this->instance->getRotationY());
        $this->assertEquals(-0.8421, $this->instance->getRotationZ());
        $this->assertEqualsWithDelta(20.4894, $this->instance->getScaleFactor(), PHP_FLOAT_EPSILON);
    }

    /**
     * testGettersReturnValuesSetBySetters
     *
     * @param string $propertyName The name of the property.
     * @param mixed $propertyValue The value of the property.
     */
    #[DataProvider('getPropertyNamesAndTestValues')]
    #[Test]
    public function gettersReturnValuesSetBySetters($propertyName, $propertyValue): void
    {
        $ucfirstPropertyName = ucfirst((string) $propertyName);

        $setter = 'set' . $ucfirstPropertyName;
        $getter = 'get' . $ucfirstPropertyName;

        // Assert setters return the object.
        $object = $this->instance->$setter($propertyValue);
        $this->assertInstanceOf(HelmertTransform::class, $object);
        $this->assertEquals($this->instance, $object);

        $this->assertEquals($propertyValue, $this->instance->$getter());
    }

    /**
     * Data provider to provide test values for each property of the object.
     *
     * @return Iterator<(int | string), mixed> An array, each element an array containing a property name and a test value.
     */
    public static function getPropertyNamesAndTestValues(): Iterator
    {
        yield ['translationX', -446.448];
        yield ['translationY', 125.157];
        yield ['translationZ', -542.060];
        yield ['rotationX', -0.1502];
        yield ['rotationY', -0.2470];
        yield ['rotationZ', -0.8421];
        yield ['scaleFactor', 20.4894];
    }

    #[Test]
    public function getReverseHelmertTransformNegatesEachProperty(): void
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
