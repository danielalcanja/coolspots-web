<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsGeoCity
 *
 * @ORM\Table(name="cs_geo_city")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsGeoCityRepository")
 */
class CsGeoCity
{
    /**
     * @var string
	 * @ORM\Column(name="cityName", type="string" length="200", nullable=false)
     */
    private $cityName;

    /**
     * @var string
	 * @ORM\Column(name="enabled", type="string", length=1, nullable=false)
     */
    private $enabled;

    /**
     * @var string
	 * @ORM\Column(name="deleted", type="string", length=1, nullable=false)
     */
    private $deleted;


    /**
     * @var integer
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsGeoCountry
     */
    private $idCountry;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsGeoState
     */
    private $idState;


    /**
     * Set cityName
     *
     * @param string $cityName
     * @return CsGeoCity
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    
        return $this;
    }

    /**
     * Get cityName
     *
     * @return string 
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set enabled
     *
     * @param string $enabled
     * @return CsGeoCity
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return string 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set deleted
     *
     * @param string $deleted
     * @return CsGeoCity
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    
        return $this;
    }

    /**
     * Get deleted
     *
     * @return string 
     */
    public function getDeleted()
    {
        return $this->deleted;
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

    /**
     * Set idCountry
     *
     * @param \CoolSpots\SiteBundle\Entity\CsGeoCountry $idCountry
     * @return CsGeoCity
     */
    public function setIdCountry(\CoolSpots\SiteBundle\Entity\CsGeoCountry $idCountry = null)
    {
        $this->idCountry = $idCountry;
    
        return $this;
    }

    /**
     * Get idCountry
     *
     * @return \CoolSpots\SiteBundle\Entity\CsGeoCountry 
     */
    public function getIdCountry()
    {
        return $this->idCountry;
    }

    /**
     * Set idState
     *
     * @param \CoolSpots\SiteBundle\Entity\CsGeoState $idState
     * @return CsGeoCity
     */
    public function setIdState(\CoolSpots\SiteBundle\Entity\CsGeoState $idState = null)
    {
        $this->idState = $idState;
    
        return $this;
    }

    /**
     * Get idState
     *
     * @return \CoolSpots\SiteBundle\Entity\CsGeoState 
     */
    public function getIdState()
    {
        return $this->idState;
    }
}