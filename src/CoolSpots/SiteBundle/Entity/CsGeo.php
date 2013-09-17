<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsGeo
 *
 * @ORM\Table(name="cs_geo")
 * @ORM\Entity
 */
class CsGeo
{
    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=10, nullable=false)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=false)
     */
    private $city;

    /**
     * @var integer
     *
     * @ORM\Column(name="region", type="integer", nullable=false)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="region_name", type="string", length=50, nullable=false)
     */
    private $regionName;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=20, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=20, nullable=false)
     */
    private $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return CsGeo
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    
        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return CsGeo
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set region
     *
     * @param integer $region
     * @return CsGeo
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return integer 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set regionName
     *
     * @param string $regionName
     * @return CsGeo
     */
    public function setRegionName($regionName)
    {
        $this->regionName = $regionName;
    
        return $this;
    }

    /**
     * Get regionName
     *
     * @return string 
     */
    public function getRegionName()
    {
        return $this->regionName;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return CsGeo
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return CsGeo
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}