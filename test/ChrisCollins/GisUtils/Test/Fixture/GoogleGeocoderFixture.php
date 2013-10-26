<?php

namespace ChrisCollins\GisUtils\Test\Fixture;

use ChrisCollins\GisUtils\Test\Fixture\AbstractJsonLoadingFixture;

/**
 * GoogleGeocoderFixture
 *
 * Test fixture to provide example response content from the Google Geocoder service.
 */
class GoogleGeocoderFixture extends AbstractJsonLoadingFixture
{
    /**
     * {@inheritdoc}
     */
    protected function getJsonDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'GoogleGeocoderJson';
    }
}
