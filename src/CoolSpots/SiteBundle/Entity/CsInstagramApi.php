<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsPics
 *
 * @ORM\Table(name="cs_instagram_api")
 * @ORM\Entity
 */
class CsInstagramApi
{
    /**
     * @var string
     *
     * @ORM\Column(name="client_id", type="string", length=40)
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="client_secret", type="string", length=40)
     */
    private $clientSecret;

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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * Set clientId
     *
     * @param string $clientId
     * @return CsInstagramApi
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    
        return $this;
    }

    /**
     * Get clientId
     *
     * @return string 
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set clientSecret
     *
     * @param string $clientSecret
     * @return CsInstagramApi
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    
        return $this;
    }

    /**
     * Get clientSecret
     *
     * @return string 
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set enabled
     *
     * @param string $enabled
     * @return CsInstagramApi
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
     * @return CsInstagramApi
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
}