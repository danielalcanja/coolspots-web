<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsLocation
 *
 * @ORM\Table(name="cs_location")
 * @ORM\Entity
 */
class CsLocation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_instagram", type="integer", nullable=false)
     */
    private $idInstagram;

    /**
     * @var string
     *
     * @ORM\Column(name="id_foursquare", type="string", length=100, nullable=false)
     */
    private $idFoursquare;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
	
	/**
     * @var string
	 * 
	 * @ORM\Column(name="slug", type="string", length=100, nullable=false)
     */
    private $slug;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     */
    private $dateUpdated;

    /**
     * @var string
     *
     * @ORM\Column(name="cover_pic", type="string", length=150, nullable=true)
     */
    private $coverPic;

    /**
     * @var integer
     *
     * @ORM\Column(name="checkins_count", type="integer", nullable=true)
     */
    private $checkinsCount;

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
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=120, nullable=true)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="postal_code", type="integer", nullable=true)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=4, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsCategory
     *
     * @ORM\ManyToOne(targetEntity="CoolSpots\SiteBundle\Entity\CsCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     * })
     */
    private $idCategory;

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
     * Set idInstagram
     *
     * @param integer $idInstagram
     * @return CsLocation
     */
    public function setIdInstagram($idInstagram)
    {
        $this->idInstagram = $idInstagram;
    
        return $this;
    }

    /**
     * Get idInstagram
     *
     * @return integer 
     */
    public function getIdInstagram()
    {
        return $this->idInstagram;
    }

    /**
     * Set idFoursquare
     *
     * @param string $idFoursquare
     * @return CsLocation
     */
    public function setIdFoursquare($idFoursquare)
    {
        $this->idFoursquare = $idFoursquare;
    
        return $this;
    }

    /**
     * Get idFoursquare
     *
     * @return string 
     */
    public function getIdFoursquare()
    {
        return $this->idFoursquare;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CsLocation
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return CsLocation
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
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return CsLocation
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

    /**
     * Set coverPic
     *
     * @param string $coverPic
     * @return CsLocation
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
     * Set checkinsCount
     *
     * @param integer $checkinsCount
     * @return CsLocation
     */
    public function setCheckinsCount($checkinsCount)
    {
        $this->checkinsCount = $checkinsCount;
    
        return $this;
    }

    /**
     * Get checkinsCount
     *
     * @return integer 
     */
    public function getCheckinsCount()
    {
        return $this->checkinsCount;
    }

    /**
     * Set lastMinId
     *
     * @param string $lastMinId
     * @return CsLocation
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
     * @return CsLocation
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
     * Set address
     *
     * @param string $address
     * @return CsLocation
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set postalCode
     *
     * @param integer $postalCode
     * @return CsLocation
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    
        return $this;
    }

    /**
     * Get postalCode
     *
     * @return integer 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return CsLocation
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
     * Set state
     *
     * @param string $state
     * @return CsLocation
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return CsLocation
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Set idCategory
     *
     * @param \CoolSpots\SiteBundle\Entity\CsCategory $idCategory
     * @return CsLocation
     */
    public function setIdCategory(\CoolSpots\SiteBundle\Entity\CsCategory $idCategory = null)
    {
        $this->idCategory = $idCategory;
    
        return $this;
    }

    /**
     * Get idCategory
     *
     * @return \CoolSpots\SiteBundle\Entity\CsCategory 
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * Set idGeo
     *
     * @param \CoolSpots\SiteBundle\Entity\CsGeo $idGeo
     * @return CsLocation
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
    /**
     * @var string
     */
    private $enabled;

    /**
     * @var string
     */
    private $deleted;


    /**
     * Set enabled
     *
     * @param string $enabled
     * @return CsLocation
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
     * @return CsLocation
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
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return CsLocation
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }
}