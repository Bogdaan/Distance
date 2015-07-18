<?php

namespace Distance\Tests;

use Distance\ProviderPool;
use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Exception\ProviderError;

class ProviderPoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Distance\Exception\PoolError
     */
    public function testEmptyPool()
    {
        $pool = new ProviderPool();

        $c1 = new Coordinate(10, 10);
        $c2 = new Coordinate(20, 20);

        $pool->getDistance($c1, $c2);
    }

    public function testProviderSwitch()
    {
        $provider1 = $this
            ->getMockBuilder('Distance\Provider\HaversinaProvider')
            ->setMethods(['getUid', 'getDistance'])
            ->getMock();

        $provider1
            ->method('getUid')
            ->will( $this->returnValue('phpunit1') );

        $provider1
            ->method('getDistance')
            ->will(
                $this->throwException( new ProviderError('provider error hit') )
            );

        $provider2 = $this
            ->getMockBuilder('Distance\Provider\HaversinaProvider')
            ->setMethods(['getUid', 'getDistance'])
            ->getMock();

        $provider2
            ->method('getUid')
            ->will( $this->returnValue('phpunit2') );

        $provider2
            ->method('getDistance')
            ->will( $this->returnValue(new Distance(10, 'phpunit2')) );

        $pool = new ProviderPool(array(
            $provider1,
            $provider2,
        ));


        $distance = $pool->getDistance(new Coordinate(10, 10), new Coordinate(20, 20));


        $this->assertEquals(10, $distance->getDistance());
        $this->assertEquals('phpunit2', $distance->getProviderUid());
        $this->assertEquals(1, count($pool->getProviders()));
    }

    /**
     * @expectedException Distance\Exception\PoolError
     */
    public function testDisableProvider()
    {
        $pool = new ProviderPool();
        $pool->disableProvider('no-exist-provider');
    }
}
