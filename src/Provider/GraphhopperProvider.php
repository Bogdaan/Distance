<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Exception\ProviderError;

/**
 * @author hcbogdan
 */
class GraphhopperProvider extends HttpProvider implements ProviderInterface
{
    public $baseUrl = 'https://graphhopper.com/api/1/matrix';

    /**
     * {@inheritDoc}
     */
    public function getUid()
    {
        return 'GraphhopperProvider';
    }

    /**
     * {@inheritDoc}
     */
    public function getDistance(Coordinate $from, Coordinate $to)
    {
        if($from==$to){
            return $this->createDistance(0);
        }


        return $this->createDistance(0);
    }
}
