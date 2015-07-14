<?php

namespace Distance;

use Distance\Provider\ProviderInterface;
use Distance\Model\Coordinate;
use Distance\Exception\PoolError;
use Distance\Exception\ProviderError;


/**
 * Fail-safe set of providers.
 * Switch providers on service exception.
 */
class ProviderPool implements ProviderInterface
{
    /**
     * @var array list of available providers
     */
    private $providers = array();

    /**
     * @var ProviderInterface active provider
     */
    private $activeProvider;

    /**
     * @param array list of providers
     */
    public function __construct($providers)
    {
        $this->registerProviders($providers);
    }

    /**
     * Register new provider
     */
    public function registerProvider(ProviderInterface $provider)
    {
        $this->providers[ $provider->getUid() ] = $provider;
    }

    /**
     * Register set of providers
     *
     * @param array set of ProviderInterface objects
     */
    public function registerProviders($providers)
    {
        $this->providers = array();

        foreach($providers as $i)
        {
            $this->registerProvider($i);
        }

        $this->activeProvider = reset($this->providers);

        return $this;
    }

    /**
     * Fali-safe distance between two coordinates in meters
     * @return integer distance in menters
     */
    public function getDistance(Coordinate $from, Coordinate $to)
    {
        try {
            return $this->getProvider()->getDistance($from, $to);

        } catch (PoolError $exception) {
            throw $exception;

        } catch (ProviderError $exception) {
            $this->disableActiveProvider();
            return $this->getDistance($from, $to);

        }
    }

    /**
     * Rotate providers. Switch to next provider
     *
     * @return ProviderPool
     */
    public function disableProvider($uid)
    {
        if(isset( $this->providers[$uid] ))
        {
            unset( $this->providers[$uid] );

            if(empty($this->providers))
                throw new PoolError('All providers disabled');

            if($this->activeProvider->getUid() == $uid)
                $this->activeProvider = reset($this->providers);
        }

        return $this;
    }

    /**
     * Active provider
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        if(empty($this->providers))
            throw new PoolError('All providers disabled');

        return $this->activeProvider;
    }

    /**
     * Disable active provider
     */
    protected function disableActiveProvider()
    {
        return $this->disableProvider( $this->activeProvider->getUid() );
    }
}
