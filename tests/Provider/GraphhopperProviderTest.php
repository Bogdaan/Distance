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
        $coords = [
            new Coordinate(10, 10),
            new Coordinate(20, 20),
            new Coordinate(30, 30),
            new Coordinate(40, 40),
        ];

        $client = $this->getSuccessMatrixClient();
        $provider = new GraphhopperProvider($client, ['key'=>'MY-KEY']);

        $matrix = $provider->getDistanceMatrix($coords);

        for($i=0; $i<=3; $i++)
        {
            $this->assertEquals(0, $matrix->getDistance($coords[$i], $coords[$i], Distance::UNIT_METER));
            $this->assertEquals(0, $matrix->getDistance($coords[$i], $coords[$i], Distance::UNIT_KILOMETER));
        }
    }

    protected function getSuccessClient()
    {
        return $this->getClientWithBody('{"distances":[[1]],"info":{"copyrights":["GraphHopper","OpenStreetMap contributors"]}}');
    }

    /**
     * 4x4 matrix
     */
    protected function getSuccessMatrixClient()
    {
        return $this->getClientWithBody('{"distances":[[0,40867,43057,22529],[42743,0,34604,36118],[41737,33070,0,26334],[22540,35946,25547,0]],"times":[[0,3260,2860,1853],[3286,0,2431,2845],[2880,2421,0,2047],[1854,2821,2074,0]],"info":{"copyrights":["GraphHopper","OpenStreetMap contributors"]}}');
    }


}
