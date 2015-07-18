<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Model\DistanceMatrix;

/**
 * Haversina formula provider
 * @author hcbogdan
 */
class HaversinaProvider extends Provider implements ProviderInterface
{
    const EARTH_RADIUS = 6371000;

    /**
     * {@inheritDoc}
     */
    public function getUid()
    {
        return 'HaversinaProvider';
    }

    /**
     * {@inheritDoc}
     */
    public function getDistance(Coordinate $from, Coordinate $to)
    {
        $diffLatitude = $from->getLatitude() - $to->getLatitude();
        $diffLongitude = $from->getLongitude() - $to->getLongitude();

        $a = sin($diffLatitude / 2) * sin($diffLatitude / 2) +
            cos($from->getLatitude()) * cos($to->getLatitude()) *
            sin($diffLongitude / 2) * sin($diffLongitude / 2);

        $c = 2 * asin(sqrt($a));

        $distance = self::EARTH_RADIUS * $c;

        return $this->createDistance( $distance * 1000 );
    }

    /**
     * {@inheritDoc}
     */
    protected function queryDistanceMatrix($normalized)
    {
        $distances = [];
        foreach($normailized as $iidx => $i)
        {
            $distances[$iidx] = [];
            foreach($normailized as $jidx => $j)
            {
                $distances[$iidx][$jidx] = $this->getDistance($i, $j);
            }
        }

        return new DistanceMatrix($normalized, $distances);
    }
}
