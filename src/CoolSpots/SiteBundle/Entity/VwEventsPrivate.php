<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwEventsPrivate
 *
 * @ORM\Table(name="vw_events_private")
 * @ORM\Entity
 */
class VwEventsPrivate
{
    /**
     * @var \DateTime
	 * @ORM\Column(name="date_added", type="timestamp", nullable=false)
     */
    private $dateAdded;

    /**
     * @var string
	 * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
	 * @ORM\Column(name="tag", type="string", length=100, nullable=false)
     */
    private $tag;

    /**
     * @var string
	 * @ORM\Column(name="cover_pic", type="string", length=150, nullable=true)
     */
    private $coverPic;

    /**
     * @var \DateTime
	 * @ORM\Column(name="date_start", type="timestamp", nullable=false)
     */
    private $dateStart;

    /**
     * @var \DateTime
	 * @ORM\Column(name="date_end", type="timestamp", nullable=false)
     */
    private $dateEnd;

    /**
     * @var string
	 * @ORM\Column(name="public", type="string", length=1, nullable=false)
     */
    private $public;

    /**
     * @var integer
	 * @ORM\Column(name="id_country", type="integer", nullable=false)
     */
    private $idCountry;

    /**
     * @var string
	 * @ORM\Column(name="country_name", type="string", length=100, nullable=false)
     */
    private $countryName;

    /**
     * @var integer
	 * @ORM\Column(name="id_state", type="integer", nullable=false)
     */
    private $idState;

    /**
     * @var string
	 * @ORM\Column(name="state_name", type="string", length=100, nullable=false)
     */
    private $stateName;

    /**
     * @var integer
	 * @ORM\Column(name="id_city", type="integer", nullable=false)
     */
    private $idCity;

    /**
     * @var string
	 * @ORM\Column(name="city_name", type="string", length=100, nullable=false)
     */
    private $cityName;

    /**
     * @var string
	 * @ORM\Column(name="full_name", type="string", length=100, nullable=false)
     */
    private $fullName;

    /**
     * @var string
	 * @ORM\Column(name="username", type="string", length=100, nullable=false)
     */
    private $username;

    /**
     * @var string
	 * @ORM\Column(name="picture_profile", type="string", length=100, nullable=true)
     */
    private $profilePicture;

    /**
     * @var integer
	 * @ORM\Id
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
     * @ORM\Id
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
     * @ORM\Id
     * @ORM\Column(name="id_user_friend", type="integer", nullable=false)
     */
    private $idUserFriend;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsLocation
     *
     * @ORM\Column(name="id_location", type="integer", nullable=false)
     */
    private $idLocation;


    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return VwEventsPrivate
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
     * @return VwEventsPrivate
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
     * @return VwEventsPrivate
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
     * @return VwEventsPrivate
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
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return VwEventsPrivate
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    
        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return VwEventsPrivate
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    
        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set public
     *
     * @param string $public
     * @return VwEventsPrivate
     */
    public function setPublic($public)
    {
        $this->public = $public;
    
        return $this;
    }

    /**
     * Get public
     *
     * @return string 
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set idCountry
     *
     * @param integer $idCountry
     * @return VwEventsPrivate
     */
    public function setIdCountry($idCountry)
    {
        $this->idCountry = $idCountry;
    
        return $this;
    }

    /**
     * Get idCountry
     *
     * @return integer 
     */
    public function getIdCountry()
    {
        return $this->idCountry;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     * @return VwEventsPrivate
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
     * Set idState
     *
     * @param integer $idState
     * @return VwEventsPrivate
     */
    public function setIdState($idState)
    {
        $this->idState = $idState;
    
        return $this;
    }

    /**
     * Get idState
     *
     * @return integer 
     */
    public function getIdState()
    {
        return $this->idState;
    }

    /**
     * Set stateName
     *
     * @param string $stateName
     * @return VwEventsPrivate
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
     * Set idCity
     *
     * @param integer $idCity
     * @return VwEventsPrivate
     */
    public function setIdCity($idCity)
    {
        $this->idCity = $idCity;
    
        return $this;
    }

    /**
     * Get idCity
     *
     * @return integer 
     */
    public function getIdCity()
    {
        return $this->idCity;
    }

    /**
     * Set cityName
     *
     * @param string $cityName
     * @return VwEventsPrivate
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    
        return $this;
    }

    /**
     * Get cityName
     *
     * @return string 
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return VwEventsPrivate
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    
        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return VwEventsPrivate
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return VwEventsPrivate
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    
        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string 
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
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
     * @return VwEventsPrivate
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
     * Set idUserFriend
     *
     * @param \CoolSpots\SiteBundle\Entity\CsUsers $idUserFriend
     * @return VwEventsPrivate
     */
    public function setIdUserFriend(\CoolSpots\SiteBundle\Entity\CsUsers $idUserFriend = null)
    {
        $this->idUserFriend = $idUserFriend;
    
        return $this;
    }

    /**
     * Get idUserFriend
     *
     * @return \CoolSpots\SiteBundle\Entity\CsUsers 
     */
    public function getIdUserFriend()
    {
        return $this->idUserFriend;
    }

    /**
     * Set idLocation
     *
     * @param \CoolSpots\SiteBundle\Entity\CsLocation $idLocation
     * @return VwEventsPrivate
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