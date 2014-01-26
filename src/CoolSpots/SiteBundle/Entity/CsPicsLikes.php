<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsPicsLikes
 * @ORM\Table(name="cs_pics_likes")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsPicsLikesRepository")
 */
class CsPicsLikes
{
    /**
     * @var \DateTime
	 * @ORM\Column(name="date_added", type="timestamp", nullable=false)
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
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
	 * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsPics
	 * @ORM\Column(name="id_pic", type="integer", nullable=false)
     */
    private $idPic;


    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return CsPicsLikes
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
     * @return CsPicsLikes
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
     * Set idUser
     *
     * @param \CoolSpots\SiteBundle\Entity\CsUsers $idUser
     * @return CsPicsLikes
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
     * Set idPic
     *
     * @param \CoolSpots\SiteBundle\Entity\CsPics $idPic
     * @return CsPicsLikes
     */
    public function setIdPic(\CoolSpots\SiteBundle\Entity\CsPics $idPic = null)
    {
        $this->idPic = $idPic;
    
        return $this;
    }

    /**
     * Get idPic
     *
     * @return \CoolSpots\SiteBundle\Entity\CsPics 
     */
    public function getIdPic()
    {
        return $this->idPic;
    }
}
