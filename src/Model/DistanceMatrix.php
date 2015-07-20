<?php

namespace Distance\Model;

/**
 * Distance matrix representation
 * @author hcbogdan
 */
class DistanceMatrix implements DistanceInterface
{
    private $distanceIndex;

    /**
     * @param array $coordinates list of Coordinate object (in order of distance)
     * @param array $distances   list of distance in meters
     */
    public function __construct($coordinates, $distances)
    {
        // reset to list
        $coordinates = array_values($coordinates);

        $coordsCount = count($coordinates);

        if($coordsCount != count($distances)) {
            throw new \Exception('count of distances = (count of coordinates ^ 2)');
        }

        foreach($coordinates as $iidx => $i)
        {
            if($coordsCount != count($distances[ $iidx ])) {
                throw new \Exception('count of distances = (count of coordinates ^ 2)');
            }

            foreach($coordinates as $jidx => $j)
            {
                $akey = $i.$j;
                $this->distanceIndex[ $akey ] = $distances[ $iidx ][ $jidx ];
            }
        }

    }

    /**
     * Get distance by coords
     *
     * @param  Coordinate  $from start point
     * @param  Coordinate  $to   end point
     * @param  integer     $unit optional units
     * @return double      distance in units
     */
    public function getDistance(Coordinate $from, Coordinate $to, $unit=null)
    {
        $dist = $this->getDistanceByIndex($from.$to);

        switch ($unit) {
            case self::UNIT_KILOMETER:
                return $dist / 1000;
                break;

            case self::UNIT_MILE:
                return $dist * 0.000621371192;
                break;

            default:
                return $dist;
                break;
        }
    }

    /**
     * Raw distance array
     */
    public function toArray()
    {
        return $this->distanceIndex;
    }

    /**
     * Internal distance index
     * @param  string $index lat-lng of coords
     * @return double        found distance
     */
    protected function getDistanceByIndex($index)
    {
        if(!isset($this->distanceIndex[ $index ])) {
            throw new Exception('distance not exist in this matrix');
        }

        return $this->distanceIndex[ $index ];
    }
}
