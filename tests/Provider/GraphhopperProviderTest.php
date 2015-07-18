<?php

namespace Distance\Tests\Provider;

use Distance\Model\Distance;
use Distance\Model\Coordinate;
use Distance\Provider\GraphhopperProvider;

/**
 * @author hcbogdan
 */
class GraphhopperProviderTest extends HttpProviderTestBase
{
    // TODO
    public function testEqualDistance()
    {
        $client = $this->getClientWithException();

        $provider = new GraphhopperProvider($client, ['key'=>'MY-KEY']);

        $coord = new Coordinate(30, 30);
        $dist = $provider->getDistance($coord, $coord);

        $this->assertEquals(0, $dist->getDistance(Distance::UNIT_METER));
    }

    public function testProviderSuccess()
    {
        $coord1 = new Coordinate(30, 30);
        $coord2 = new Coordinate(10, 10);

        $client = $this->getSuccessClient();
        $provider = new GraphhopperProvider($client, ['key'=>'MY-KEY']);

        $dist = $provider->getDistance($coord1, $coord2);

        $this->assertEquals(1, $dist->getDistance(Distance::UNIT_METER));
    }

    /**
     * @expectedException Distance\Exception\ProviderError
     */
    public function testNoKey()
    {
        $provider = new GraphhopperProvider($this->getSuccessClient());
    }

    public function testDistanceMatrix()
    {
    }

    protected function getSuccessClient()
    {
        return $this->getClientWithBody('{"distances":[[1]],"info":{"copyrights":["GraphHopper","OpenStreetMap contributors"]}}');
    }
}
