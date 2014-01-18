<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwFriends
 * @ORM\Table(name="vw_friends")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\VwFriendsRepository")
 */
class VwFriends
{
    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $profilePicture;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
     */
    private $idUser;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
     */
    private $idUserFriend;


    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return VwFriends
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
     * Set username
     *
     * @param string $username
     * @return VwFriends
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
     * Set fullName
     *
     * @param string $fullName
     * @return VwFriends
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
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return VwFriends
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
     * @return VwFriends
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
     * @return VwFriends
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
}