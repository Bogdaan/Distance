<?php

namespace Distance\Model;

/**
 * Distance entity
 * @author hcbogdan
 */
class Distance implements DistanceInterface
{

    /**
     * @var double $distance representation in meters
     */
    private $distance;

    /**
     * @var mixed $provider uid of provider
     */
    private $provider;

    /**
     * Create distance object
     * @param double $distamce distance in meters
     * @param string $provider provider uid
     */
    public function __construct($distance, $provider = null)
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

    /**
     * Convert to array
     */
    public function toArray()
    {
        return [
            'lat' => $this->getLat(),
            'lng' => $this->getLng(),
        ];
    }
}
