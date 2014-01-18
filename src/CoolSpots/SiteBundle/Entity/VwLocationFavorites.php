<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwLocationFavorites
 * @ORM\Table(name="vw_location_favorites")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\VwLocationFavoritesRepository")
 */
class VwLocationFavorites
{
    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var string
     */
    private $deleted;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $lastPic;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
     */
    private $idUser;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsLocation
     */
    private $idLocation;


    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return VwLocationFavorites
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    
        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set deleted
     *
     * @param string $deleted
     * @return VwLocationFavorites
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
     * Set name
     *
     * @param string $name
     * @return VwLocationFavorites
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return VwLocationFavorites
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set lastPic
     *
     * @param string $lastPic
     * @return VwLocationFavorites
     */
    public function setLastPic($lastPic)
    {
        $this->lastPic = $lastPic;
    
        return $this;
    }

    /**
     * Get lastPic
     *
     * @return string 
     */
    public function getLastPic()
    {
        return $this->lastPic;
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
     * Set idUser
     *
     * @param \CoolSpots\SiteBundle\Entity\CsUsers $idUser
     * @return VwLocationFavorites
     */
    public function setIdUser(\CoolSpots\SiteBundle\Entity\CsUsers $idUser = null)
    {
        $this->idUser = $idUser;
    
        return $this;
    }

    /**
     * Get idUser
     *
     * @return \CoolSpots\SiteBundle\Entity\CsUsers 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idLocation
     *
     * @param \CoolSpots\SiteBundle\Entity\CsLocation $idLocation
     * @return VwLocationFavorites
     */
    public function setIdLocation(\CoolSpots\SiteBundle\Entity\CsLocation $idLocation = null)
    {
        $this->idLocation = $idLocation;
    
        return $this;
    }

    /**
     * Get idLocation
     *
     * @return \CoolSpots\SiteBundle\Entity\CsLocation 
     */
    public function getIdLocation()
    {
        return $this->idLocation;
    }
    /**
     * @var \DateTime
     */
    private $dateUpdated;


    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return VwLocationFavorites
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    
        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }
}