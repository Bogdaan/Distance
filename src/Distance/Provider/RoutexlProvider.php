<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;

/**
 * Routexl.com (paid) distance matrix provider.
 *
 * @author hcbogdan
 */
class RoutexlProvider extends HttpProvider implements ProviderInterface
{

    /**
     * {@inheritDoc}
     */
    public function getUid()
    {
        return 'RoutexlProvider';
    }
}
