<?php

namespace Distance\Provider;

use Ivory\HttpAdapter\HttpAdapterInterface;

/**
 * Base provider class for http providers.
 *
 * @author hcbogdan
 */
abstract class HttpProvider extends Provider
{
    /**
     * query adapter
     * @var HttpAdapterInterface
     */
    private $adapter;

    /**
     * @param HttpAdapterInterface $adapter query adapter
     */
    public function __construct(HttpAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return HttpAdapterInterface query adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
