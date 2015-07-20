PHP distance library
====================

[![Build
Status](https://secure.travis-ci.org/Bogdaan/Distance.png)](http://travis-ci.org/Bogdaan/Distance)

This library provide:
 - distance calculation
 - distance matrix calculation

Note that the library does not provide geocoding features (for these purposes, you can use [this library](https://github.com/geocoder-php/Geocoder)).

Example
-------

```php

use Distance\Model\Distance;
use Distance\Model\Coordinate;
use Distance\Exception\PoolError;

// for HTTP providers
$client = new GuzzleHttp\Client();

// fail-safe distance source
$pool = new Distance\ProviderPool([

    // Google distance matrix provider
    new Distance\Provider\GoogleProvider($client),

    // OSRM server with distance_table plugin
    new Distance\Provider\OsrmProvider($client, [
        'baseUrl' => 'http://localhost:2233/',
    ]),

    // graphhopper service
    new Distance\Provider\GraphhopperProvider($client,[
        'key' => 'YOU_API_KEY'
    ]),

    // routexl.com distance matrix
    new Distance\Provider\RoutexlProvider($client, [
        'username' => 'YOU_API_USERNAME',
        'password' => 'YOU_API_PASSWORD',
    ]),

    // optinal (If all previous fails) - math provider
    new Distance\Provider\HaversinaProvider(),
]);

// GPS coordinates
$coords = [
    new Coordinate(48.41, 34.81),
    new Coordinate(48.43, 35.22),
    new Coordinate(48.43, 36.22).
];

// case1: single distance
$distanceObject = $pool->getDistance($coords[0], $coords[1]);
$distanceInMeters = $distanceObject->getDistance(Distance::UNIT_METER);
$distanceInMiles = $distanceObject->getDistance(Distance::UNIT_MILE);


// case2: distance matrix 3x3
$matrix = $pool->getDistanceMatrix($coords);
$distanceInMeters = $matrix->getDistance($coords[0], $coords[1], Distance::UNIT_METER);
```


Instalation
-----------

Using [composer](http://getcomposer.org):
```
$ composer require willdurand/geocoder
```


Providers
---------

Currently supported providers:

- [Google Distance Matrinx](https://developers.google.com/maps/documentation/javascript/distancematrix)
- [OSRM](https://github.com/Project-OSRM/osrm-backend)
- [Graphhopper](https://graphhopper.com/)
- [Routexl](http://www.routexl.nl/blog/api/)
