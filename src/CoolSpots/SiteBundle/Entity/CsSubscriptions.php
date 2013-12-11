<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsSubscriptions
 *
 * @ORM\Table(name="cs_subscriptions")
 * @ORM\Entity
 */
class CsSubscriptions
{
    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=30, nullable=true)
     */
    private $object;

    /**
     * @var string
     *
     * @ORM\Column(name="object_id", type="string", length=30, nullable=true)
     */
    private $objectId;

    /**
     * @var string
     *
     * @ORM\Column(name="changed_aspect", type="string", length=100, nullable=true)
     */
    private $changedAspect;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=true)
     */
    private $time;

    /**
     * @var string
	 * 
	 * @ORM\Column(name="updated", type="char", length=1, nullable=false)
     */
    private $updated;

    /**
     * @var integer
	 * 
	 * @ORM\Column(name="cycle_count", type="integer", nullable=false)
     */
    private $cycleCount;

    /**
     * @var integer
	 * 
	 * @ORM\Column(name="subscription_id", type="integer", nullable=false)
     */
    private $subscriptionId;

	/**
     * @var integer
	 * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * Set object
     *
     * @param string $object
     * @return CsSubscriptions
     */
    public function setObject($object)
    {
        $this->object = $object;
    
        return $this;
    }

    /**
     * Get object
     *
     * @return string 
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set objectId
     *
     * @param string $objectId
     * @return CsSubscriptions
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    
        return $this;
    }

    /**
     * Get objectId
     *
     * @return string 
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set changedAspect
     *
     * @param string $changedAspect
     * @return CsSubscriptions
     */
    public function setChangedAspect($changedAspect)
    {
        $this->changedAspect = $changedAspect;
    
        return $this;
    }

    /**
     * Get changedAspect
     *
     * @return string 
     */
    public function getChangedAspect()
    {
        return $this->changedAspect;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return CsSubscriptions
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set updated
     *
     * @param string $updated
     * @return CsSubscriptions
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return string 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set cycleCount
     *
     * @param integer $cycleCount
     * @return CsSubscriptions
     */
    public function setCycleCount($cycleCount)
    {
        $this->cycleCount = $cycleCount;
    
        return $this;
    }

    /**
     * Get cycleCount
     *
     * @return integer 
     */
    public function getCycleCount()
    {
        return $this->cycleCount;
    }

    /**
     * Set subscriptionId
     *
     * @param integer $subscriptionId
     * @return CsSubscriptions
     */
    public function setSubscriptionId($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    
        return $this;
    }
	
    /**
     * Get subscriptionId
     *
     * @return integer 
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
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
     * @var \CoolSpots\SiteBundle\Entity\CsInstagramApi
     */
    private $idInstagramApi;


    /**
     * Set idInstagramApi
     *
     * @param \CoolSpots\SiteBundle\Entity\CsInstagramApi $idInstagramApi
     * @return CsSubscriptions
     */
    public function setIdInstagramApi(\CoolSpots\SiteBundle\Entity\CsInstagramApi $idInstagramApi = null)
    {
        $this->idInstagramApi = $idInstagramApi;
    
        return $this;
    }

    /**
     * Get idInstagramApi
     *
     * @return \CoolSpots\SiteBundle\Entity\CsInstagramApi 
     */
    public function getIdInstagramApi()
    {
        return $this->idInstagramApi;
    }
}