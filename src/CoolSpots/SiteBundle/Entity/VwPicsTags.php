<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwPicsTags
 * @ORM\Table(name="vw_pics_tags")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\VwPicsTagsRepository")
 */
class VwPicsTags
{
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
     * @var \CoolSpots\SiteBundle\Entity\CsLocation
     */
    private $idLocation;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var \DateTime
     */
    private $createdTime;

    /**
     * @var string
     */
    private $lowResolution;

    /**
     * @var string
     */
    private $thumbnail;

    /**
     * @var string
     */
    private $standardResolution;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var integer
     */
    private $likesCount;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var integer
	 * @ORM\Id
     */
    private $id;


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

    /**
     * Set type
     *
     * @param integer $type
     * @return VwPicsTags
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
     * @return VwPicsTags
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
     * @return VwPicsTags
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
     * @return VwPicsTags
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
     * @return VwPicsTags
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
     * @return VwPicsTags
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
     * @return VwPicsTags
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
     * @return VwPicsTags
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
     * Set tag
     *
     * @param string $tag
     * @return VwPicsTags
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    
        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
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
