<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Model\DistanceMatrix;
use Distance\Exception\ProviderError;
use Distance\Exception\QuotaExceeded;

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
        if($from==$to){
            return $this->createDistance(0);
        }

        $params                 = $this->getParams();
        $params['origins']      = $from.'';
        $params['destinations'] = $to.'';
        $params['units']        = 'metric';

        $json = $this->getQueryJson($params);

        if(!isset($json)) {
            throw new ProviderError('Wrong json responce');
        }

        if($json['status'] == 'OVER_QUERY_LIMIT') {
            throw new QuotaExceeded();
        } elseif($json['status'] != 'OK') {
            throw new ProviderError('Provider return status: '.$json->status);
        }

        try {
            $distance = \igorw\get_in($json, ['rows', 0, 'elements', 0, 'distance', 'value']);

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
        $normilizedStr = implode($normalized, '|');

        $params                 = $this->getParams();
        $params['origins']      = $normilizedStr;
        $params['destinations'] = $normilizedStr;
        $params['units']        = 'metric';

        $json = $this->getQueryJson($params);

        if(!isset($json)) {
            throw new ProviderError('Wrong json responce');
        }

        if($json['status'] == 'OVER_QUERY_LIMIT') {
            throw new QuotaExceeded();
        } elseif($json['status'] != 'OK') {
            throw new ProviderError('Provider return status: '.$json->status);
        }

        $distances = [];
        foreach($json['rows'] as $rowIdx => $row)
        {
            foreach($row['elements'] as $eIdx => $element)
            {
                $distances[$rowIdx][$eIdx] = $element['distance']['value'];
            }
        }

        return new DistanceMatrix($normalized, $distances);
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
