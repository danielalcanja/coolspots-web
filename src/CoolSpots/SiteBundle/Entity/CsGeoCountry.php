<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsGeoCountry
  *
 * @ORM\Table(name="cs_geo_country")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsGeoCountryRepository")
*/
class CsGeoCountry
{
    /**
     * @var string
	 * @ORM\Column(name="country_name", type="string", length=50, nullable=false)
     */
    private $countryName;

    /**
     * @var string
	 * @ORM\Column(name="country_code", type="string", length=5, nullable=false)
     */
    private $countryCode;

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
     * Set countryName
     *
     * @param string $countryName
     * @return CsGeoCountry
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    
        return $this;
    }

    /**
     * Get countryName
     *
     * @return string 
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return CsGeoCountry
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
     * Set enabled
     *
     * @param string $enabled
     * @return CsGeoCountry
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
     * @return CsGeoCountry
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
}