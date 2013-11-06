<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VwInstagramApi
 *
 * @ORM\Table(name="vw_instagram_api")
 * @ORM\Entity(repositoryClass="CoolSpots\SiteBundle\Entity\VwInstagramApiRepository")
 */
class VwInstagramApi
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

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
     * @var integer
     *
     * @ORM\Column(name="total_subscriptions", type="integer")
     */
    private $totalSubscriptions;


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
     * Set clientId
     *
     * @param string $clientId
     * @return VwInstagramApi
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
     * @return VwInstagramApi
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
     * Set totalSubscriptions
     *
     * @param integer $totalSubscriptions
     * @return VwInstagramApi
     */
    public function setTotalSubscriptions($totalSubscriptions)
    {
        $this->totalSubscriptions = $totalSubscriptions;
    
        return $this;
    }

    /**
     * Get totalSubscriptions
     *
     * @return integer 
     */
    public function getTotalSubscriptions()
    {
        return $this->totalSubscriptions;
    }
}
