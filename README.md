GIS Utils
=========

A PHP library for performing GIS tasks, such as geocoding addresses and transforming coordinates.

[https://github.com/chriscollins/gis-utils](https://github.com/chriscollins/gis-utils)

Installation
------------

Require in your project via composer as follows:

```json
{
    "require": {
        "chriscollins/gis-utils": "~1.0.0"
    }
}
```

Run `composer install`.

Usage
-----

The majority of functionality is accessed via the `ChrisCollins\GisUtils\Coordinate\LatLong` class.

### Creating a LatLong object manually

You will first need to create a `ChrisCollins\GisUtils\Datum\Datum` object for the [datum](http://en.wikipedia.org/wiki/Datum_(geodesy)) you wish to represent.  Datums are composed of a name, an `ChrisCollins\GisUtils\Ellipsoid\Ellipsoid` representing the Earth and a `ChrisCollins\GisUtils\Equation\HelmertTransform` to convert a coordinate in the commonly used [WGS84 datum](http://en.wikipedia.org/wiki/World_Geodetic_System) to a coordinate in your datum:

```php
$airy1830Ellipsoid = new ChrisCollins\GisUtils\Ellipsoid\Ellipsoid(
    'AIRY_1830', // Name.
    6377563.396, // Semi-major axis.
    6356256.910, // Semi-minor axis.
    299.3249646 // Flattening.
);

$osgb36ToWgs84HelmertTransform = new ChrisCollins\GisUtils\Equation\HelmertTransform(
    -446.448, // Translation X.
    125.157, // Translation Y.
    -542.060, // Translation Z.
    -0.1502, // Rotation X.
    -0.2470, // Rotation Y.
    -0.8421, // Rotation Z.
    20.4894 // Scale factor.
);

$osgb36Datum = new Datum('OSGB36', $airy1830Ellipsoid, $osgb36ToWgs84HelmertTransform);
```

The `ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory`, `ChrisCollins\GisUtils\Datum\DatumFactory` and `ChrisCollins\GisUtils\Equation\HelmertTransformFactory` classes are available to create a few commonly used options:

```php
$ellipsoidFactory = new ChrisCollins\GisUtils\Ellipsoid\EllipsoidFactory();
$helmertTransformFactory = new ChrisCollins\GisUtils\Equation\HelmertTransformFactory();

$datumFactory = new ChrisCollins\GisUtils\Datum\DatumFactory(
    $ellipsoidFactory,
    $helmertTransformFactory
);

$wgs84Datum = $datumFactory->create(ChrisCollins\GisUtils\Datum\DatumFactory::DATUM_WGS84);
```

There is also the `ChrisCollins\GisUtils\Facade` class which handles the dependency injection for you via [Pimple](http://pimple.sensiolabs.org), but is a little less flexible:

```php
$facade = new ChrisCollins\GisUtils\Facade();

$wgs84Datum = $facade->createDatum(ChrisCollins\GisUtils\Datum\DatumFactory::DATUM_WGS84);
```

Once you have your `Datum`, you can create `LatLong` objects if you know your chosen point's latitude, longitude, and height in your chosen datum:

```php
$latLong = new ChrisCollins\GisUtils\Coordinate\LatLong(
    51.88328, // Latitude.
    -3.43684, // Longitude.
    886, // Height in metres.
    $wgs84Datum // Datum.
);
```

### Creating a LatLong object from a lookup

This library also provides a method of creating `LatLong` objects with the `ChrisCollins\GisUtils\Lookup\GoogleLookup` class, which looks up the coordinates of an address by using [Google's geocoding service](https://developers.google.com/maps/documentation/geocoding).  Please take note of Google's terms of use and API limits for using the geocoder service.  Coordinates created by this service will use the WGS84 datum.  To use the class, you will need to instantiate an `ChrisCollins\GisUtils\Address\Address` (or your own class that implements `ChrisCollins\GisUtils\Address\AddressInterface`):

```php
$address = new ChrisCollins\GisUtils\Address\Address();
$address->setHouseNumber(10)
    ->setAddress1('Downing Street')
    ->setTown('London')
    ->setCountry('England')
    ->setPostcode('SW1A 2AA');
```

You can then perform the lookup as follows:

```php
$googleLookup = new ChrisCollins\GisUtils\Lookup\GoogleLookup(
    $datumFactory,
    ChrisCollins\GeneralUtils\Curl\CurlHandle(),
    new ChrisCollins\GeneralUtils\Json\JsonCodec()
);

$latLong = $googleLookup->addressToLatLong($address);
```

Again, this functionality is available via the `Facade` class, if you want to have the dependency injection handled for you:

```php
$facade = new ChrisCollins\GisUtils\Facade();

$latLong = $facade->googleAddressToLatLong($address);
```

### Functionality available

Once you have a `LatLong` object, you can perform calculations to calculate distances between it and other `LatLong` objects ([Spherical law of cosines](http://en.wikipedia.org/wiki/Spherical_law_of_cosines) and the more accurate, more computationally expensive [Vincenty formula](http://en.wikipedia.org/wiki/Vincenty's_formulae) are both supported), convert it to equivalent coordinates in other datums, calculate initial and final bearings to other `LatLongs` and calculate the destination `LatLong` that would be reached if a given initial bearing was followed for a certain distance.  There is also support for converting the `LatLong` to cartesian coordinates (`ChrisCollins\GisUtils\Coordinate\CartesianCoordinate`) and back.

Generate the code documentation via phpdox for further information.
