<?php

namespace Distance\Tests\Provider;

use Distance\Provider\HaversinaProvider;
use Distance\Model\Coordinate;

/**
 * @author hcbogdan
 */
class HaversinaProviderTest extends ProviderTestBase
{
    protected function getProvider()
    {
        return new HaversinaProvider();
    }

    public function testEqualDistance()
    {
        $coord = new Coordinate(30, 30);

        $dist = $this->getProvider()->getDistance($coord, $coord);

        $this->assertEquals(0, $dist->getDistance());
    }

    public function testDistanceMatrix()
    {
    }
}
