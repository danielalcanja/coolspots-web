<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwLocation
 *
 * @ORM\Table(name="vw_location")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\VwLocationRepository")
 */
class VwLocation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_geo", type="integer")
     */
    private $idGeo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_category", type="integer")
     */
    private $idCategory;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_instagram", type="integer")
     */
    private $idInstagram;

    /**
     * @var string
     *
     * @ORM\Column(name="id_foursquare", type="string", length=100)
     */
    private $idFoursquare;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @var string
     *
     * @ORM\Column(name="cover_pic", type="string", length=150)
     */
    private $coverPic;

    /**
     * @var integer
     *
     * @ORM\Column(name="checkins_count", type="integer")
     */
    private $checkinsCount;

    /**
     * @var string
     *
     * @ORM\Column(name="last_min_id", type="string", length=100)
     */
    private $lastMinId;

    /**
     * @var string
     *
     * @ORM\Column(name="next_max_id", type="string", length=100)
     */
    private $nextMaxId;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_timestamp", type="integer")
     */
    private $minTimestamp;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=120)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="postal_code", type="integer")
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=4)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="enabled", type="string", length=1)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="deleted", type="string", length=1)
     */
    private $deleted;
	
    /**
     * @var string
	 * @ORM\Column(name="country", type="string", length=5, nullable=true)
     */
    private $country;
	

    /**
     * @var string
     *
     * @ORM\Column(name="last_pic", type="string", length=150)
     */
    private $lastPic;


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
     * Set idGeo
     *
     * @param integer $idGeo
     * @return VwLocation
     */
    public function setIdGeo($idGeo)
    {
        $this->idGeo = $idGeo;
    
        return $this;
    }

    /**
     * Get idGeo
     *
     * @return integer 
     */
    public function getIdGeo()
    {
        return $this->idGeo;
    }

    /**
     * Set idCategory
     *
     * @param integer $idCategory
     * @return VwLocation
     */
    public function setIdCategory($idCategory)
    {
        $this->idCategory = $idCategory;
    
        return $this;
    }

    /**
     * Get idCategory
     *
     * @return integer 
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * Set idInstagram
     *
     * @param integer $idInstagram
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * Set minTimestamp
     *
     * @param integer $minTimestamp
     * @return VwLocation
     */
    public function setMinTimestamp($minTimestamp)
    {
        $this->minTimestamp = $minTimestamp;
    
        return $this;
    }

    /**
     * Get minTimestamp
     *
     * @return integer 
     */
    public function getMinTimestamp()
    {
        return $this->minTimestamp;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * @return VwLocation
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
     * Set enabled
     *
     * @param string $enabled
     * @return VwLocation
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
     * @return VwLocation
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
     * Set lastPic
     *
     * @param string $lastPic
     * @return VwLocation
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
     * Set country
     *
     * @param string $country
     * @return CsLocation
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }
	
}