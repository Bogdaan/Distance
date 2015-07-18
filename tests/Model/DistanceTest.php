<?php

namespace Distance\Tests;

use Distance\Model\Distance;

class DistanceTest extends \PHPUnit_Framework_TestCase
{
    protected $distance;

    protected function setUp()
    {
        $this->distance = new Distance(1000, 'phpunit');
    }

    public function testDistance()
    {
        $this->assertEquals(1000, $this->distance->getDistance(Distance::UNIT_METER));
        $this->assertEquals(1, floor($this->distance->getDistance(Distance::UNIT_KILOMETER)));
        $this->assertEquals(0, floor($this->distance->getDistance(Distance::UNIT_MILE)));
        $this->assertEquals('phpunit', $this->distance->getProviderUid());
    }
}
