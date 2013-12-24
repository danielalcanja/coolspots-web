<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsInbox
 * 
 * @ORM\Table(name="cs_inbox")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsInboxRepository")
 */
class CsInbox
{
    /**
     * @var integer
	 * @ORM\Column(name="id_user_to", type="integer", nullable=false)
     */
    private $idUserTo;

    /**
     * @var \DateTime
	 * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @var string
	 * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @var string
	 * @ORM\Column(name="message", type="string", length=1000, nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
	 * @ORM\Column(name="date_read", type="datetime", nullable=false)
     */
    private $dateRead;

    /**
     * @var string
	 * @ORM\Column(name="deleted", type="string", length=1, nullable=false)
     */
    private $deleted;

    /**
     * @var integer
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsUsers
	 * @ORM\Column(name="id_user_from", type="integer", nullable=false)
     */
    private $idUserFrom;


    /**
     * Set idUserTo
     *
     * @param integer $idUserTo
     * @return CsInbox
     */
    public function setIdUserTo($idUserTo)
    {
        $this->idUserTo = $idUserTo;
    
        return $this;
    }

    /**
     * Get idUserTo
     *
     * @return integer 
     */
    public function getIdUserTo()
    {
        return $this->idUserTo;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return CsInbox
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
     * Set title
     *
     * @param string $title
     * @return CsInbox
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return CsInbox
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
     * Set dateRead
     *
     * @param \DateTime $dateRead
     * @return CsInbox
     */
    public function setDateRead($dateRead)
    {
        $this->dateRead = $dateRead;
    
        return $this;
    }

    /**
     * Get dateRead
     *
     * @return \DateTime 
     */
    public function getDateRead()
    {
        return $this->dateRead;
    }

    /**
     * Set deleted
     *
     * @param string $deleted
     * @return CsInbox
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
     * Set idUserFrom
     *
     * @param \CoolSpots\SiteBundle\Entity\CsUsers $idUserFrom
     * @return CsInbox
     */
    public function setIdUserFrom(\CoolSpots\SiteBundle\Entity\CsUsers $idUserFrom = null)
    {
        $this->idUserFrom = $idUserFrom;
    
        return $this;
    }

    /**
     * Get idUserFrom
     *
     * @return \CoolSpots\SiteBundle\Entity\CsUsers 
     */
    public function getIdUserFrom()
    {
        return $this->idUserFrom;
    }
}
