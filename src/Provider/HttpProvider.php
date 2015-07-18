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
     * Query client.
     * @var ClientInterface
     */
    private $client;

    /**
     * Additional query params.
     * @var array
     */
    private $params;

    /**
     * Create provider instance
     *
     * @param ClientInterface $client
     * @param array           $additionalParams additinal query parameters
     * @see GoogleProvider::$params
     */
    public function __construct(ClientInterface $client, $additionalParams = array())
    {
        $this->client = $client;
        $this->params = $additionalParams;
    }

    /**
     * @return ClientInterface query client
     */
    public function getClient()
    {
        return $this->client;
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
