<?php

namespace Distance\Tests;

use Distance\Model\Distance;
use Distance\Model\DistanceMatrix;
use Distance\Model\Coordinate;

/**
 * @author hcbogdan
 */
class DistanceMatrixTest extends \PHPUnit_Framework_TestCase
{
    public function testMatrixConstruct()
    {
        $coords = [
            new Coordinate(10, 10),
            new Coordinate(20, 20),
            new Coordinate(30, 30),
        ];

        $distances = [];
        for($i=0; $i<3; $i++)
        {
            for($j=0; $j<3; $j++)
            {
                $distances[ $i ][ $j ] = $i+$j;
            }
        }

        $matrix = new DistanceMatrix($coords, $distances);


        for($i=0; $i<3; $i++)
        {
            for($j=0; $j<3; $j++)
            {
                $this->assertEquals($i+$j, $matrix->getDistance($coords[$i], $coords[$j]) );
            }
        }
    }

    /**
     * @expectedException \Exception
     */
    public function testConstructExceptions()
    {
        $coords = [
            new Coordinate(10, 10),
            new Coordinate(20, 20),
        ];

        $distances = [
            [0, 0],
            [0],
        ];

        $matrix = new DistanceMatrix($coords, $distances);
    }

    /**
     * @expectedException \Exception
     */
    public function testConstructExceptions2()
    {
        $coords = [
            new Coordinate(10, 10),
            new Coordinate(20, 20),
        ];

        $distances = [
            [0, 0],
        ];

        $matrix = new DistanceMatrix($coords, $distances);
    }

    /**
     * @expectedException \Exception
     */
    public function testConstructExceptions3()
    {
        $coords = [
            new Coordinate(10, 10),
        ];

        $distances = [
            [0, 0],
            [0, 0],
        ];

        $matrix = new DistanceMatrix($coords, $distances);
    }
}
