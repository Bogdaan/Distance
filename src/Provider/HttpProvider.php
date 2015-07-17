<?php

namespace Distance\Provider;

use GuzzleHttp\ClientInterface;

/**
 * Base provider class for http providers.
 *
 * @author hcbogdan
 */
abstract class HttpProvider extends Provider
{
    /**
     * Query adapter.
     * @var ClientInterface
     */
    private $adapter;

    /**
     * Additional query params.
     * @var array
     */
    private $params;

    /**
     * Create provider instance
     *
     * @param ClientInterface $adapter
     * @param array           $additionalParams additinal query parameters
     * @see GoogleProvider::$params
     */
    public function __construct(ClientInterface $adapter, $additionalParams = array())
    {
        $this->adapter = $adapter;
        $this->params = $additionalParams;
    }

    /**
     * @return ClientInterface query adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Additional params
     *
     * @return array query params
     */
    public function getParams()
    {
        return $this->params;
    }
}
