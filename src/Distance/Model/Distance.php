<?php

namespace Distance\Model;

/**
 * Distance entity
 * @author hcbogdan
 */
class Distance
{
    /**
     * availible units
     */
    const UNIT_METER = 1;
    const UNIT_KILOMETER = 2;
    const UNIT_MILE = 3;


    /**
     * @var double $distance representation in meters
     */
    private $distance;

    /**
     * @var string $provider uid of provider
     */
    private $provider;

    /**
     * Create distance object
     *
     * @param string $provider provider uid
     * @param double $distamce distance in meters
     */
    public function __construct($provider, $distance)
    {
        $this->distance = $distance;
        $this->provider = $provider;
    }

    /**
     * Get distance value
     *
     * @param integer $unit unit of distance
     * @see Distance::UNIT_METER
     * @see Distance::UNIT_KILOMETER
     * @see Distance::UNIT_MILE
     *
     * @return double distance value in meters (by default)
     */
    public function getDistance($unit = null)
    {
        switch ($unit) {
            case self::UNIT_KILOMETER:
                return $this->distance / 1000;
                break;

            case self::UNIT_MILE:
                return $this->distance * 0.000621371192;
                break;

            default:
                return $this->distance;
                break;
        }
    }

    /**
     * Get provider uid
     *
     * @return string provider UID
     */
    public function getProviderUid()
    {
        return $this->provider;
    }
}
