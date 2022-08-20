<?php

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
            $message = "Failed asserting that {$actual} matches expected {$expected} when rounded to " .
                "{$significantFigures} significant figures.";
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
    public function assertEqualsWithinTolerance($expected, $actual, $tolerance, $message = null): void
    {
        $difference = abs($expected - $actual);

        if ($message === null) {
            $message = "Failed asserting that {$actual} matches expected {$expected} within tolerance of {$tolerance}"
                . " (difference {$difference}).";
        }

        $this->assertTrue($difference <= $tolerance, $message);
    }

    /**
     * Assert that a float is within a certain percentage tolerance.
     *
     * @param float $expected The expected value.
     * @param float $actual The actual value.
     * @param float $tolerance The percentage tolerance.
     * @param string|null $message An optional override message.
     */
    public function assertEqualsWithinPercentageTolerance($expected, $actual, $tolerance, $message = null): void
    {
        $percentageDifference = abs($expected - $actual) / $expected * 100;

        if ($message === null) {
            $message = "Failed asserting that {$actual} matches expected {$expected} within tolerance of {$tolerance}%"
                . " (difference {$percentageDifference}%).";
        }

        $this->assertTrue($percentageDifference <= $tolerance, $message);
    }
}
