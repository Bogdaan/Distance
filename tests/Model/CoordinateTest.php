<?php

namespace Distance\Tests;

use Distance\Model\Coordinate;

class CoordinateTest extends \PHPUnit_Framework_TestCase
{
    protected $coord;

    protected function setUp()
    {
        $this->coord = new Coordinate(10, 20);
    }

    public function testCoord()
    {
        $this->assertEquals(10, $this->coord->getLat());
        $this->assertEquals(20, $this->coord->getLng());
        $this->assertEquals('10, 20', $this->coord.'');
    }
}
