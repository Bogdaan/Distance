<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Exception\ProviderError;

/**
 * Base provider class.
 *
 * @author hcbogdan
 */
abstract class Provider
{
    /**
     * Calculate distance matrix.
     *
     * @param array list of coordinates
     * @return array distance matrix
     */
    public function getDistanceMatrix($coordinates)
    {
        $normalized = $this->normalizeMatrix( $coordinates );
        return $this->queryDistanceMatrix( $normalized );
    }

    /**
     * Create distance object, set provider uid.
     * @param  double $distance distance in meters
     * @return Distance         object of distance
     */
    protected function createDistance($distance)
    {
        return new Distance($distance, $this->getUid());
    }

    /**
     * Internal queries
     */
    protected function queryDistanceMatrix($normalized)
    {
        throw new ProviderError('not implemented');
    }

    /**
     * Create normalized list
     * @param  array $coords list of coords
     * @return array         distance matrix init
     */
    protected function normalizeMatrix($coords)
    {
        if(!is_array($coords) || empty($coords)) {
            throw new ProviderError('specify coordinates');
        }

        $normalized = [];
        foreach($coords as $i)
        {
            if($i instanceof Coordinate){
                $normalized[ $i.'' ] = $i;
            } elseif(is_array($i)) {

                if(isset($i['lat']) && isset($i['lng'])) {
                    $coord = new Coordinate($i['lat'], $i['lng']);
                    $normalized[ $coord.'' ] = $coord;

                } elseif( count($i)==2 ) {
                    $coord = new Coordinate($i[0], $i[1]);
                    $normalized[ $coord.'' ] = $coord;

                } else {
                    throw new ProviderError('wrong data format');
                }

            } else {
                throw new ProviderError('wrong data format');
            }
        }

        return $normalized;
    }
}
