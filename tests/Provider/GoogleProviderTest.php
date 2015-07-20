<?php

namespace Distance\Tests\Provider;

use Distance\Model\Distance;
use Distance\Model\Coordinate;
use Distance\Provider\GoogleProvider;

/**
 * @author hcbogdan
 */
class GoogleProviderTest extends HttpProviderTestBase
{
    // TODO
    public function testEqualDistance()
    {
        $client = $this->getClientWithException();

        $provider = new GoogleProvider($client);

        $coord = new Coordinate(30, 30);
        $dist = $provider->getDistance($coord, $coord);

        $this->assertEquals(0, $dist->getDistance(Distance::UNIT_METER));
    }

    public function testProviderSuccess()
    {
        $coord1 = new Coordinate(30, 30);
        $coord2 = new Coordinate(10, 10);

        $client = $this->getSuccessClient();
        $provider = new GoogleProvider($client);

        $dist = $provider->getDistance($coord1, $coord2);

        $this->assertEquals(1, $dist->getDistance(Distance::UNIT_METER));
    }

    public function testDistanceMatrix()
    {
        $coords = [
            new Coordinate(10, 10),
            new Coordinate(20, 20)
        ];

        $client = $this->getSuccessMatrixClient();
        $provider = new GoogleProvider($client);

        $matrix = $provider->getDistanceMatrix($coords);

        $this->assertEquals(0, $matrix->getDistance($coords[0], $coords[0], Distance::UNIT_METER));
        $this->assertEquals(0, $matrix->getDistance($coords[1], $coords[1], Distance::UNIT_METER));
    }

    /**
     * @expectedException Distance\Exception\QuotaExceeded
     */
    public function testProviderQuota()
    {
        $coord1 = new Coordinate(30, 30);
        $coord2 = new Coordinate(10, 10);

        $client = $this->getQuotaClient();
        $provider = new GoogleProvider($client);

        $dist = $provider->getDistance($coord1, $coord2);
    }


    protected function getQuotaClient()
    {
        return $this->getClientWithBody('{
           "destination_addresses" : [ "Unnamed Road" ],
           "origin_addresses" : [ "Unnamed Road" ],
           "rows" : [],
           "status" : "OVER_QUERY_LIMIT"
        }');
    }


    protected function getSuccessClient()
    {
        return $this->getClientWithBody('{
           "destination_addresses" : [ "Unnamed Road" ],
           "origin_addresses" : [ "Unnamed Road" ],
           "rows" : [
              {
                 "elements" : [
                    {
                       "distance" : {
                          "text" : "1 м",
                          "value" : 1
                       },
                       "duration" : {
                          "text" : "1 мин.",
                          "value" : 1
                       },
                       "status" : "OK"
                    }
                 ]
              }
           ],
           "status" : "OK"
        }');
    }

    /**
     * 2x2 matrix
     */
    protected function getSuccessMatrixClient()
    {
        return $this->getClientWithBody('{
   "destination_addresses" : [ "Vancouver, BC, Canada", "Seattle, Washington, États-Unis" ],
   "origin_addresses" : [ "Vancouver, BC, Canada", "Seattle, Washington, États-Unis" ],
   "rows" : [
      {
         "elements" : [
            {
               "distance" : {
                  "text" : "1 m",
                  "value" : 0
               },
               "duration" : {
                  "text" : "1 minute",
                  "value" : 0
               },
               "status" : "OK"
            },
            {
               "distance" : {
                  "text" : "270 km",
                  "value" : 270100
               },
               "duration" : {
                  "text" : "15 heures 25 minutes",
                  "value" : 55514
               },
               "status" : "OK"
            }
         ]
      },
      {
         "elements" : [
            {
               "distance" : {
                  "text" : "268 km",
                  "value" : 267501
               },
               "duration" : {
                  "text" : "15 heures 3 minutes",
                  "value" : 54198
               },
               "status" : "OK"
            },
            {
               "distance" : {
                  "text" : "1 m",
                  "value" : 0
               },
               "duration" : {
                  "text" : "1 minute",
                  "value" : 0
               },
               "status" : "OK"
            }
         ]
      }
   ],
   "status" : "OK"
}');

    }


}
