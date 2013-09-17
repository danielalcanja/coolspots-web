<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsCategory
 *
 * @ORM\Table(name="cs_category")
 * @ORM\Entity
 */
class CsCategory
{
    /**
     * @var string
     *
     * @ORM\Column(name="exid", type="string", length=100, nullable=true)
     */
    private $exid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set exid
     *
     * @param string $exid
     * @return CsCategory
     */
    public function setExid($exid)
    {
        $this->exid = $exid;
    
        return $this;
    }

    /**
     * Get exid
     *
     * @return string 
     */
    public function getExid()
    {
        return $this->exid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CsCategory
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}