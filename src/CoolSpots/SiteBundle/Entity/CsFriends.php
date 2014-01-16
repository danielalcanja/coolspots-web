<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsFriends
 * 
 * @ORM\Table(name="cs_friends")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsFriendsRepository")
 */
class CsFriends
{
    /**
     * @var \DateTime
	 * @ORM\Column(name="date_added", type="timestamp", nullable=false)
     */
    private $dateAdded;

    /**
     * @var integer
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
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
	 * @ORM\Column(name="id_user_friend", type="integer", nullable=false)
     */
    private $idUserFriend;


    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return CsFriends
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
     * @return CsFriends
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
     * @return CsFriends
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