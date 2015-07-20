<?php

namespace Distance;

use Distance\Provider\ProviderInterface;
use Distance\Model\Coordinate;
use Distance\Exception\PoolError;
use Distance\Exception\ProviderError;

/**
 * Fail-safe set of providers.
 * Switch providers on service exception.
 *
 * @author hcbogdan
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
    private $activeProvider = null;

    /**
     * @param array $providers list of providers
     */
    public function __construct($providers = null)
    {
        if(is_array($providers)) {
            $this->registerProviders($providers);
        }
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
    public function registerProviders(array $providers)
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
     * {@inheritDoc}
     */
    public function getUid()
    {
        return 'ProviderPool';
    }

    /**
     * Fali-safe distance between two coordinates in meters
     * @return Distance distance object
     */
    public function getDistance(Coordinate $from, Coordinate $to)
    {
        try {
            return $this->getActiveProvider()->getDistance($from, $to);

        } catch (PoolError $exception) {
            throw $exception;

        } catch (ProviderError $exception) {
            $this->disableActiveProvider();
            return $this->getDistance($from, $to);

        }
    }

    /**
     * Fail-safe distance matrix calculator.
     * @param array list of coordinates
     * @return DistanceMatrix
     */
    public function getDistanceMatrix($coordinates)
    {
        try {
            return $this->getActiveProvider()->getDistanceMatrix($coordinates);

        } catch (PoolError $exception) {
            throw $exception;

        } catch (ProviderError $exception) {
            $this->disableActiveProvider();
            return $this->getDistanceMatrix($coordinates);

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

            if($this->activeProvider->getUid() == $uid)
                $this->activeProvider = reset($this->providers);
        }

        if(empty($this->providers))
            throw new PoolError('All providers disabled');

        return $this;
    }

    /**
     * @return array enabled providers
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * Active provider
     *
     * @return ProviderInterface
     */
    protected function getActiveProvider()
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
