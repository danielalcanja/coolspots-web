<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsGeoState
 *
 * @ORM\Table(name="cs_geo_state")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsGeoStateRepository")
 */
class CsGeoState
{
    /**
     * @var string
	 * @ORM\Column(name="state_name", type="string", length=50, nullable=false)
     */
    private $stateName;

    /**
     * @var string
	 * @ORM\Column(name= "state_abbr", type="string", length=2, nullable=false)
     */
    private $stateAbbr;

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
     * Set stateName
     *
     * @param string $stateName
     * @return CsGeoState
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;
    
        return $this;
    }

    /**
     * Get stateName
     *
     * @return string 
     */
    public function getStateName()
    {
        return $this->stateName;
    }

    /**
     * Set stateAbbr
     *
     * @param string $stateAbbr
     * @return CsGeoState
     */
    public function setStateAbbr($stateAbbr)
    {
        $this->stateAbbr = $stateAbbr;
    
        return $this;
    }

    /**
     * Get stateAbbr
     *
     * @return string 
     */
    public function getStateAbbr()
    {
        return $this->stateAbbr;
    }

    /**
     * Set enabled
     *
     * @param string $enabled
     * @return CsGeoState
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
     * @return CsGeoState
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
     * @return CsGeoState
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
}