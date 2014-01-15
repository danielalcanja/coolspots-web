<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsFriendsRequest
 * 
 * @ORM\Table(name="cs_friends_request")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsFriendsRequestRepository")
 */
class CsFriendsRequest
{
    /**
     * @var integer
	 * @ORM\Column(name="id_user_friend", type="integer", nullable=false)
     */
    private $idUserFriend;

    /**
     * @var \DateTime
	 * @ORM\Column(name="date_added", type="timestamp", nullable=false)
     */
    private $dateAdded;

    /**
     * @var string
	 * @ORM\Column(name="message", type="string", length=200, nullable=false)
     */
    private $message;

    /**
     * @var string
	 * @ORM\Column(name="status", type="string", length=1, nullable=false)
     */
    private $status;

    /**
     * @var integer
	 * @ORM\Id
	 * @ORM\GeneratedValue(stragety="IDENTITY")
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
	 * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;


    /**
     * Set idUserFriend
     *
     * @param integer $idUserFriend
     * @return CsFriendsRequest
     */
    public function setIdUserFriend($idUserFriend)
    {
        $this->idUserFriend = $idUserFriend;
    
        return $this;
    }

    /**
     * Get idUserFriend
     *
     * @return integer 
     */
    public function getIdUserFriend()
    {
        return $this->idUserFriend;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return CsFriendsRequest
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
     * Set message
     *
     * @param string $message
     * @return CsFriendsRequest
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return CsFriendsRequest
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
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
     * @return CsFriendsRequest
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