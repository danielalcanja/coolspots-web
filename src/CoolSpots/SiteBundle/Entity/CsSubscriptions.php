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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}