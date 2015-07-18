<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Exception\ProviderError;
use Distance\Exception\QuotaExceeded;

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
            throw new ProviderException('set baseUrl parameter');
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

        // convert to string
        $queryPath = $this->baseUrl.'table?loc='.$from.'&loc='.$to;


        $responce = $this
            ->getClient()
            ->get($queryPath)
            ->getBody();

        $json = json_decode( $responce, true );

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
}
