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
}
