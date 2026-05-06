<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test;

use PHPUnit\Framework\TestCase;

/**
 * AbstractTestCase
 *
 * Abstract test case class.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * Assert that a float is equal to a value if it is rounded to a given number of significant figures.
     *
     * @param float $expected The expected value.
     * @param float $actual The actual value.
     * @param int $significantFigures The number of significant figures to round to.
     * @param string|null $message An optional override message.
     */
    public function assertEqualsWhenRounded($expected, $actual, $significantFigures, $message = null): void
    {
        if ($message === null) {
            $message = sprintf('Failed asserting that %s matches expected %s when rounded to ', $actual, $expected) .
                ($significantFigures . ' significant figures.');
        }

        $this->assertEquals($expected, round($actual, $significantFigures), $message);
    }

    /**
     * Assert that a float is within a certain tolerance (epsilon).
     *
     * @param float $expected The expected value.
     * @param float $actual The actual value.
     * @param float $tolerance The tolerance.
     * @param string|null $message An optional override message.
     */
    public function assertEqualsWithinTolerance(
        float $expected,
        float $actual,
        float $tolerance,
        ?string $message = null
    ): void {
        $difference = abs($expected - $actual);

        if ($message === null) {
            $message = sprintf(
                'Failed asserting that %s matches expected %s within tolerance of %s',
                $actual,
                $expected,
                $tolerance
            )
                . sprintf(' (difference %s).', $difference);
        }

        $this->assertLessThanOrEqual($tolerance, $difference, $message);
    }

    /**
     * Assert that a float is within a certain percentage tolerance.
     *
     * @param float $expected The expected value.
     * @param float $actual The actual value.
     * @param float $tolerance The percentage tolerance.
     * @param string|null $message An optional override message.
     */
    public function assertEqualsWithinPercentageTolerance(
        float $expected,
        float $actual,
        float $tolerance,
        ?string $message = null
    ): void {
        $percentageDifference = abs($expected - $actual) / $expected * 100;

        if ($message === null) {
            $message = sprintf(
                'Failed asserting that %s matches expected %s within tolerance of %s%%',
                $actual,
                $expected,
                $tolerance
            )
                . sprintf(' (difference %s%%).', $percentageDifference);
        }

        $this->assertLessThanOrEqual($tolerance, $percentageDifference, $message);
    }
}
