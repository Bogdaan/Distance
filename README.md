PHP distance library
====================

This library provide distance calculation for geo applications.
Library does not provide geocoding features (for these purposes, you can use [this library](https://github.com/geocoder-php/Geocoder) ).

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

    // routexl.com distance matrix
    new Distance\Provider\RoutexlProvider($client, [
        'username' => 'YOU_API_USERNAME',
        'password' => 'YOU_API_PASSWORD',
    ]),

    // optinal (If all previous fails) - math provider
    new Distance\Provider\HaversinaProvider(),
]);

// GPS coordinates
$point1 = new Coordinate(48.41, 34.81);
$point2 = new Coordinate(48.43, 35.22);


$distanceObject = $pool->getDistance($point1, $point2);

$distanceInMeters = $distanceObject->getDistance(Distance::UNIT_METER);
$distanceInMiles = $distanceObject->getDistance(Distance::UNIT_MILE);

```


Instalation
-----------

- composer lib
- documentation


Providers
---------

Currently supported providers:

- [Google Distance Matrinx](https://developers.google.com/maps/documentation/javascript/distancematrix)
- [OSRM](https://github.com/Project-OSRM/osrm-backend)
- Graphhopper
- Routexl
