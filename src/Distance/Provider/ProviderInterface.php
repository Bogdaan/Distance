<?php

namespace Distance;

use Distance\Model\Coordinate;

/**
 * Distance service abstraction
 */
interface ProviderInterface
{

    /**
     * Calculate distance in meters between two coordinates
     *
     * @param Coordinate route start point
     * @param Coordinate route end point
     *
     * @return integer distance in meters
     */
    public function getDistance(Coordinate $from, Coordinate $to);
}
