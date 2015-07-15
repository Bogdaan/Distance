<?php

namespace Distance\Provider;

use Distance\Model\Coordinate;
use Distance\Model\Distance;
use Distance\Exception\ProviderError;

/**
 * Google distance matrix provider.
 *
 * @author hcbogdan
 */
class GoogleProvider extends HttpProvider implements ProviderInterface
{
    const GOOGLE_MATRIX = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    /**
     * Additional request params. For example:
     * - key: api key
     * - mode: driving, walking, transit
     * - avoid: tolls, highways, ferries
     *
     * @var array
     */
    private $params;

    /**
     * Create provider instance
     * @param HttpAdapterInterface $adapter
     * @param array                $additionalParams additinal query parameters
     * @see GoogleProvider::$params
     */
    public function __construct(HttpAdapterInterface $adapter, $additionalParams = array())
    {
        parent::__construct($adapter);

        $this->params = $additionalParams;
    }

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
        $params = $this->params;

        // convert to string
        $params['origins'] = $from.'';
        $params['destinations'] = $to.'';
        $params['units'] = 'metric';

        $responce = $this
            ->getAdapter()
            ->get(self::GOOGLE_MATRIX, $params)
            ->getBody();

        $json = json_decode( $responce );

        if(!isset($json)) {
            throw new ProviderError('Wrong json responce');
        }

        if($json->status != 'OK') {
            throw new ProviderError('Provider return status: '.$json->status);
        }

        if(!isset($json->rows[0])
        || !isset($json->rows[0]->elements[0]) ) {
            throw new ProviderError('Provider responce format changed');
        }

        return $this->createDistance($json->rows[0]->elements[0]->distance->value);
    }
}
