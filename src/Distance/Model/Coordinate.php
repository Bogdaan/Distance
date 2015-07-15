<?php

namespace Distance\Model;

/**
 * Coordinate entity
 * @author hcbogdan
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
     * create coordinate
     * @param double $lat
     * @param double $lng
     */
    public function __construct($lat, $lng)
    {
        $this->latitude  = $lat;
        $this->longitude = $lng;
    }

    /**
     * string value
     */
    public function __toString()
    {
        return $this->getLat().', '.$this->getLng();
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
