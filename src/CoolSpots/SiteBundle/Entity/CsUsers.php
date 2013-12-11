<?php

namespace CoolSpots\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CsUsers
 *
 * @ORM\Table(name="cs_users")
 * @ORM\Entity
 */
class CsUsers
{
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=150, nullable=true)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", length=150, nullable=true)
     */
    private $profilePicture;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="string", length=150, nullable=true)
     */
    private $bio;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=40, nullable=true)
     */
    private $accessToken;

    /**
     * @var \DateTime
     */
    private $tokenDate;
	
    /**
     * Set username
     *
     * @param string $username
     * @return CsUsers
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
     * @return CsUsers
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
     * Set email
     *
     * @param string $email
     * @return CsUsers
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return CsUsers
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
     * Set bio
     *
     * @param string $bio
     * @return CsUsers
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    
        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }
	
    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return CsUsers
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    
        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->accessToken;
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
     * Set tokenDate
     *
     * @param \DateTime $tokenDate
     * @return CsUsers
     */
    public function setTokenDate($tokenDate)
    {
        $this->tokenDate = $tokenDate;
    
        return $this;
    }

    /**
     * Get tokenDate
     *
     * @return \DateTime 
     */
    public function getTokenDate()
    {
        return $this->tokenDate;
    }
}