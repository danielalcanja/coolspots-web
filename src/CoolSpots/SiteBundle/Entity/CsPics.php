<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsPics
 *
 * @ORM\Table(name="cs_pics")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsPicsRepository")
 */
class CsPics
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=true)
     */
    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime", nullable=true)
     */
    private $createdTime;

    /**
     * @var string
     *
     * @ORM\Column(name="low_resolution", type="string", length=150, nullable=true)
     */
    private $lowResolution;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="string", length=150, nullable=true)
     */
    private $thumbnail;

    /**
     * @var string
     *
     * @ORM\Column(name="standard_resolution", type="string", length=150, nullable=true)
     */
    private $standardResolution;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=150, nullable=true)
     */
    private $caption;

    /**
     * @var integer
     *
     * @ORM\Column(name="likes_count", type="integer", nullable=true)
     */
    private $likesCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
     *
     * @ORM\ManyToOne(targetEntity="CoolSpots\SiteBundle\Entity\CsUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;



    /**
     * Set type
     *
     * @param integer $type
     * @return CsPics
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return CsPics
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
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return CsPics
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    
        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime 
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set lowResolution
     *
     * @param string $lowResolution
     * @return CsPics
     */
    public function setLowResolution($lowResolution)
    {
        $this->lowResolution = $lowResolution;
    
        return $this;
    }

    /**
     * Get lowResolution
     *
     * @return string 
     */
    public function getLowResolution()
    {
        return $this->lowResolution;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     * @return CsPics
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    
        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string 
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set standardResolution
     *
     * @param string $standardResolution
     * @return CsPics
     */
    public function setStandardResolution($standardResolution)
    {
        $this->standardResolution = $standardResolution;
    
        return $this;
    }

    /**
     * Get standardResolution
     *
     * @return string 
     */
    public function getStandardResolution()
    {
        return $this->standardResolution;
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return CsPics
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    
        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set likesCount
     *
     * @param integer $likesCount
     * @return CsPics
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
    
        return $this;
    }

    /**
     * Get likesCount
     *
     * @return integer 
     */
    public function getLikesCount()
    {
        return $this->likesCount;
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
     * @return CsPics
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
     * @var \CoolSpots\SiteBundle\Entity\CsLocation
     */
    private $idLocation;


    /**
     * Set idLocation
     *
     * @param \CoolSpots\SiteBundle\Entity\CsLocation $idLocation
     * @return CsPics
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
}