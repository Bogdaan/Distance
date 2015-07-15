<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;

/**
 * Yandex geo matrix provider.
 *
 * @author hcbogdan
 */
class YandexProvider extends HttpProvider implements ProviderInterface
{

    /**
     * {@inheritDoc}
     */
    public function getUid()
    {
        return 'YandexProvider';
    }
}
