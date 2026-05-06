<?php

declare(strict_types=1);

namespace ChrisCollins\GisUtils\Test\Fixture;

/**
 * GoogleGeocoderFixture
 *
 * Test fixture to provide example response content from the Google Geocoder service.
 */
class GoogleGeocoderFixture extends AbstractJsonLoadingFixture
{
    protected function getJsonDirectory(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'GoogleGeocoderJson';
    }
}
