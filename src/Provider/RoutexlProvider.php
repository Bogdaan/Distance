<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Exception\ProviderError;

/**
 * Routexl.com (paid) distance matrix provider.
 *
 * @author hcbogdan
 */
class RoutexlProvider extends HttpProvider implements ProviderInterface
{
    const BASE_URL = 'https://api.routexl.nl/distances';

    /**
     * Service auth
     * @var string
     */
    private $username;

    /**
     * Service auth
     * @var string
     */
    private $password;

    /**
     * {@inheritDoc}
     */
    public function __construct(ClientInterface $client, $additionalParams = array())
    {
        if(!isset($additionalParams['username'])
        || !isset($additionalParams['password'])) {
            throw new ProviderException('wrong auth');
        }

        $this->username = $additionalParams['username'];
        $this->password = $additionalParams['password'];

        unset($additionalParams['username']);
        unset($additionalParams['password']);

        parent::__construct($client, $additionalParams);
    }

    /**
     * {@inheritDoc}
     */
    public function getUid()
    {
        return 'RoutexlProvider';
    }

    /**
     * {@inheritDoc}
     */
    public function getDistance(Coordinate $from, Coordinate $to)
    {
        $params = array(
            'locations' => array(
                array(
                    'lat' => $from->getLat(),
                    'lng' => $from->getLng(),
                ),
                array(
                    'lat' => $to->getLat(),
                    'lng' => $to->getLng(),
                ),
            ),
        );

        $json = json_decode( $this->getQueryBody($params), true );

        if(!isset($json)) {
            throw new ProviderError('Wrong json responce');
        }

        if($json['count'] <= 0) {
            throw new ProviderError('Provider not return distances');
        }


        try {
            $distance = \igorw\get_in($json, ['distances', 0, 'distance']);
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
        $params = [];
        foreach($normalized as $i)
        {
            $params['locations'][] = $i->toArray();
        }

        $countNormalized = count($normalized);

        $json = json_decode( $this->getQueryBody($params), true );

        if(!isset($json)
        || $json['count'] != $countNormalized) {
            throw new ProviderError('Wrong json responce');
        }

        $distances = [];
        foreach($json['distances'] as $dist)
        {
            $distances[ $dist['from'] ][ $dist['to'] ] = $dist['distance'];
        }

        return new DistanceMatrix($normalized, $distances);
    }

    /**
     * Create service query
     * @param  array $params query params
     * @return string        responce body
     */
    protected function getQueryBody($params)
    {
        return $this
            ->getClient()
            ->post(self::BASE_URL, array(
                'query' => $params,
                'auth'  => array(
                    $this->username,
                    $this->password,
                ),
            ))
            ->getBody();
    }
}
