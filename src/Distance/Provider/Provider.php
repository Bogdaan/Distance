<?php

namespace Distance\Provider;

use Distance\Model\Distance;

/**
 * Base provider class.
 *
 * @author hcbogdan
 */
abstract class Provider implements ProviderInterface
{
    /**
     * create distance object, set provider uid
     * @param  double $distance [description]
     * @return Distance           [description]
     */
    protected function createDistance($distance)
    {
        return new Distance($distance, $this->getUid());
    }
}
