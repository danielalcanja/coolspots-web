<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsLocationLikes
 * 
 * @ORM\Table(name="cs_location_likes")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsLocationLikesRepository")
 */
class CsLocationLikes
{
    /**
     * @var \DateTime
	 * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @var string
	 * @ORM\Column(name="deleted", type="string", length=1, nullable=false)
     */
    private $deleted;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsLocation
	 * @ORM\Column(name="id_location", type="integer", nullable=false)
     */
    private $idLocation;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
	 * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;


    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return CsLocationLikes
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
     * @return CsLocationLikes
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
     * Set idLocation
     *
     * @param \CoolSpots\SiteBundle\Entity\CsLocation $idLocation
     * @return CsLocationLikes
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
     * Set idUser
     *
     * @param \CoolSpots\SiteBundle\Entity\CsUsers $idUser
     * @return CsLocationLikes
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
}