<?php

namespace Distance\Provider;

use GuzzleHttp\ClientInterface;
use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Model\DistanceMatrix;
use Distance\Exception\ProviderError;

/**
 * @author hcbogdan
 */
class GraphhopperProvider extends HttpProvider implements ProviderInterface
{
    const BASE_URL = 'https://graphhopper.com/api/1/matrix';

    /**
     * {@inheritDoc}
     */
    public function __construct(ClientInterface $client, $additionalParams = array())
    {
        if(!isset($additionalParams['key'])) {
            throw new ProviderError('set "key" parameter');
        }

        parent::__construct($client, $additionalParams);
    }

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

        $params = $this->getParams();
        $params['from_point'] = $from->getLat().','.$from->getLng();
        $params['to_point']   = $to->getLat().','.$to->getLng();
        $params['out_array']  = 'distances';

        $json = $this->getQueryJson( $params );

        if(!isset($json)
        || !isset($json['distances'])) {
            throw new ProviderError('Wrong json responce');
        }


        try {
            $distance = \igorw\get_in($json, ['distances', 0, 0]);
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
        $params = $this->getParams();
        $params['out_array']  = 'distances';

        $params['from_point'] = [];
        $params['to_point'] = [];
        foreach($normalized as $i)
        {
            $params['from_point'][] = $i->getLat().','.$i->getLng();
            $params['to_point'][]   = $i->getLat().','.$i->getLng();
        }

        $json = $this->getQueryJson( $params );

        if(!isset($json)
        || !isset($json['distances'])) {
            throw new ProviderError('Wrong json responce');
        }

        return new DistanceMatrix($normalized, $json['distances']);
    }

    /**
     * Create service query
     * @param  array $params query params
     * @return string        responce body
     */
    protected function getQueryJson($params)
    {
        $responce = $this
            ->getClient()
            ->get(self::BASE_URL, array(
                'query' => $params,
            ))
            ->getBody();

        return json_decode( $responce, true );
    }
}
