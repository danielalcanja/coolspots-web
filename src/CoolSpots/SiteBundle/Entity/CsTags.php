<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsTags
 * @ORM\Table(name="cs_tags")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\CsTagsRepository")
 */
class CsTags
{
    /**
     * @var string
	 * @ORM\Column(name="tag", type="string", length=100, nullable=false)
     */
    private $tag;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsLocation
	 * @ORM\Column(name="id_location", type="integer", nullable=false)
     */
    private $idLocation;

    /**
     * @var \CoolSpots\SiteBundle\Entity\CsPics
	 * @ORM\Column(name="id_pic", type="integer", nullable=false)
     */
    private $idPic;


    /**
     * Set tag
     *
     * @param string $tag
     * @return CsTags
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idLocation
     *
     * @param \CoolSpots\SiteBundle\Entity\CsLocation $idLocation
     * @return CsTags
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

    /**
     * Set idPic
     *
     * @param \CoolSpots\SiteBundle\Entity\CsPics $idPic
     * @return CsTags
     */
    public function setIdPic(\CoolSpots\SiteBundle\Entity\CsPics $idPic = null)
    {
        $this->idPic = $idPic;
    
        return $this;
    }

    /**
     * Get idPic
     *
     * @return \CoolSpots\SiteBundle\Entity\CsPics 
     */
    public function getIdPic()
    {
        return $this->idPic;
    }
}
