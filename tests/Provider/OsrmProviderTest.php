<?php

namespace Distance\Tests\Provider;

use Distance\Model\Distance;
use Distance\Model\Coordinate;
use Distance\Provider\OsrmProvider;

/**
 * @author hcbogdan
 */
class OsrmProviderTest extends HttpProviderTestBase
{
    // TODO
    public function testEqualDistance()
    {
        $client = $this->getClientWithException();

        $provider = new OsrmProvider($client, ['baseUrl'=>'http://localhost:2233/']);

        $coord = new Coordinate(30, 30);
        $dist = $provider->getDistance($coord, $coord);

        $this->assertEquals(0, $dist->getDistance(Distance::UNIT_METER));
    }

    public function testProviderSuccess()
    {
        $coord1 = new Coordinate(30, 30);
        $coord2 = new Coordinate(10, 10);

        $client = $this->getSuccessClient();
        $provider = new OsrmProvider($client, ['baseUrl'=>'http://localhost:2233/']);

        $dist = $provider->getDistance($coord1, $coord2);

        $this->assertEquals(1, $dist->getDistance(Distance::UNIT_METER));
    }

    /**
     * @expectedException Distance\Exception\ProviderError
     */
    public function testNoBase()
    {
        $client = $this->getSuccessClient();
        $provider = new OsrmProvider($client);
    }

    public function testDistanceMatrix()
    {
    }
    
    protected function getSuccessClient()
    {
        return $this->getClientWithBody('{"distance_table":[[1]]}');
    }
}
