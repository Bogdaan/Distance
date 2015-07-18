<?php

namespace Distance\Tests\Provider;

use Distance\Model\Distance;
use Distance\Model\Coordinate;

class ProviderTestBase extends \PHPUnit_Framework_TestCase
{
    protected function getProvider()
    {
        throw new Exception('Method not implemented!');
    }

    protected function assertDistance(Distance $actual, Distance $expected)
    {
        return $this->assertTrue($actial == $expected);
    }
}
