<?php

namespace Distance\Tests;

use Distance\ProviderPool;
use Distance\Model\Coordinate;
use Distance\Provider\HaversinaProvider;

class ProviderPoolTest extends \PHPUnit_Framework_TestCase
{
    protected $pool;

    protected function setUp()
    {
        $this->pool = new ProviderPool();
    }

    /**
     * @expectedException Distance\Exception\PoolError
     */
    public function testEmptyPool()
    {
        $c1 = new Coordinate(10, 10);
        $c2 = new Coordinate(20, 20);

        $this->pool->getDistance($c1, $c2);
    }

}
