<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsEvents
 *
 * @ORM\Table(name="cs_events")
 * @ORM\Entity
 */
class CsEvents
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=true)
     */
    private $dateAdded;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=100, nullable=true)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="cover_pic", type="string", length=150, nullable=true)
     */
    private $coverPic;

    /**
     * @var string
     *
     * @ORM\Column(name="last_min_id", type="string", length=100, nullable=true)
     */
    private $lastMinId;

    /**
     * @var string
     *
     * @ORM\Column(name="next_max_id", type="string", length=100, nullable=true)
     */
    private $nextMaxId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsLocation
     *
     * @ORM\ManyToOne(targetEntity="CoolSpots\SiteBundle\Entity\CsLocation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_location", referencedColumnName="id")
     * })
     */
    private $idLocation;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsGeo
     *
     * @ORM\ManyToOne(targetEntity="CoolSpots\SiteBundle\Entity\CsGeo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_geo", referencedColumnName="id")
     * })
     */
    private $idGeo;



    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return CsEvents
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
     * Set name
     *
     * @param string $name
     * @return CsEvents
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
     * Set tag
     *
     * @param string $tag
     * @return CsEvents
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
     * Set coverPic
     *
     * @param string $coverPic
     * @return CsEvents
     */
    public function setCoverPic($coverPic)
    {
        $this->coverPic = $coverPic;
    
        return $this;
    }

    /**
     * Get coverPic
     *
     * @return string 
     */
    public function getCoverPic()
    {
        return $this->coverPic;
    }

    /**
     * Set lastMinId
     *
     * @param string $lastMinId
     * @return CsEvents
     */
    public function setLastMinId($lastMinId)
    {
        $this->lastMinId = $lastMinId;
    
        return $this;
    }

    /**
     * Get lastMinId
     *
     * @return string 
     */
    public function getLastMinId()
    {
        return $this->lastMinId;
    }

    /**
     * Set nextMaxId
     *
     * @param string $nextMaxId
     * @return CsEvents
     */
    public function setNextMaxId($nextMaxId)
    {
        $this->nextMaxId = $nextMaxId;
    
        return $this;
    }

    /**
     * Get nextMaxId
     *
     * @return string 
     */
    public function getNextMaxId()
    {
        return $this->nextMaxId;
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
     * @return CsEvents
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
     * Set idGeo
     *
     * @param \CoolSpots\SiteBundle\Entity\CsGeo $idGeo
     * @return CsEvents
     */
    public function setIdGeo(\CoolSpots\SiteBundle\Entity\CsGeo $idGeo = null)
    {
        $this->idGeo = $idGeo;
    
        return $this;
    }

    /**
     * Get idGeo
     *
     * @return \CoolSpots\SiteBundle\Entity\CsGeo 
     */
    public function getIdGeo()
    {
        return $this->idGeo;
    }
}