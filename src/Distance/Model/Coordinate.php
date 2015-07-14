<?php

namespace Distance\Model;

/**
 * Coordinate entity
 */
class Cooordinate
{
    /**
     * @var double
     */
    private $latitude;

    /**
     * @var double
     */
    private $longitude;

    /**
     * @param double latitude
     * @param double longitude
     */
    public function __construct($lat, $lng)
    {
        $this->latitude  = $lat;
        $this->longitude = $lng;
    }

    /**
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @see getLatitude
     * @return double
     */
    public function getLat()
    {
        return $this->getLatitude();
    }

    /**
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @see getLongitude
     * @return double
     */
    public function getLng()
    {
        return $this->getLongitude();
    }
}
