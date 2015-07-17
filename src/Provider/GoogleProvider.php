<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Exception\ProviderError;

/**
 * Google distance matrix provider.
 * Additioanl params:
 * - key: api key
 * - mode: driving, walking, transit
 * - avoid: tolls, highways, ferries
 *
 * @author hcbogdan
 */
class GoogleProvider extends HttpProvider implements ProviderInterface
{
    const BASE_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    /**
     * {@inheritDoc}
     */
    public function getUid()
    {
        return 'GoogleProvider';
    }

    /**
     * {@inheritDoc}
     */
    public function getDistance(Coordinate $from, Coordinate $to)
    {
        $params = $this->getParams();

        // convert to string
        $params['origins'] = $from.'';
        $params['destinations'] = $to.'';
        $params['units'] = 'metric';

        $responce = $this
            ->getAdapter()
            ->get(self::BASE_URL, array(
                'query' => $params,
            ))
            ->getBody();

        $json = json_decode( $responce, true );

        if(!isset($json)) {
            throw new ProviderError('Wrong json responce');
        }

        if($json['status'] != 'OK') {
            throw new ProviderError('Provider return status: '.$json->status);
        }

        try {
            $distance = igorw\get_in($data, ['rows', 0, 'elements', 0, 'distance', 'value']);

        } catch(\InvalidArgumentException $exp){
            throw new ProviderError('Provider responce format changed');

        }

        return $this->createDistance($distance);
    }
}
