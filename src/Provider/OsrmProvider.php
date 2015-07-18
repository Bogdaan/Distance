<?php

namespace Distance\Provider;

use GuzzleHttp\ClientInterface;
use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Model\DistanceMatrix;
use Distance\Exception\ProviderError;

/**
 * OSRM distance matrix provider (with distance_table plugin)
 * Required options:
 * - baseUrl - osrm service address (for example "http://localhost:2233/").
 * 		Note that public api not support distance_table.
 *
 * @author hcbogdan
 */
class OsrmProvider extends HttpProvider implements ProviderInterface
{
    private $baseUrl;

    /**
     * {@inheritDoc}
     */
    public function __construct(ClientInterface $client, $additionalParams = array())
    {
        if(!isset($additionalParams['baseUrl'])) {
            throw new ProviderError('set baseUrl parameter');
        }

        $this->baseUrl = $additionalParams['baseUrl'];

        unset($additionalParams['baseUrl']);

        parent::__construct($client, $additionalParams);
    }

    /**
     * {@inheritDoc}
     */
    public function getUid()
    {
        return 'OsrmProvider';
    }

    /**
     * {@inheritDoc}
     */
    public function getDistance(Coordinate $from, Coordinate $to)
    {
        if($from==$to){
            return $this->createDistance(0);
        }

        $json = $this
            ->getQueryJson( $this->baseUrl.'/table?loc='.$from.'&loc='.$to );

        if(!isset($json)
        || !isset($json['distance_table'])) {
            throw new ProviderError('Wrong json responce');
        }


        try {
            $distance = \igorw\get_in($json, ['distance_table', 0, 0]);
        } catch(\InvalidArgumentException $exp){
            throw new ProviderError('Provider responce format changed');
        }

        return $this->createDistance($distance);
    }

    /**
     * {@inheritDoc}
     */
    protected function queryDistanceMatrix($normalized)
    {
        $queryPath = $this->baseUrl.'/table?v=1';
        foreach($normalized as $i)
        {
            $queryPath .= '&'.$i->getLat().','.$i->getLng();
        }

        $json = $this->getQueryJson( $queryPath );

        if(!isset($json)
        || !isset($json['distance_table'])) {
            throw new ProviderError('Wrong json responce');
        }

        $distances = $json['distance_table'];

        return new DistanceMatrix($normalized, $distances);
    }

    /**
     * Create service query
     * @param  array $params query params
     * @return string        responce body
     */
    protected function getQueryJson($queryPath)
    {
        $body = $this
            ->getClient()
            ->get($queryPath)
            ->getBody();

        return json_decode($body, true);
    }
}
