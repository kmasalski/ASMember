<?php

namespace Mcc\ASMemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mcc\ASMemberBundle\Entity\Ip
 *
 * @ORM\Table(name="ip")
 * @ORM\Entity
 */
class Ip
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=false)
     */
    private $ip;
	
    /**
     * @var AutonomousSystem
     *
	 * @ORM\ManyToOne(targetEntity="AutonomousSystem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $asidentifier;

    /**
     * @var string $hostname
     *
     * @ORM\Column(name="hostname", type="string", length=500, nullable=false)
     */
    private $hostname;

    /**
     * @var integer $iswebserver
     *
     * @ORM\Column(name="isWebServer", type="integer", nullable=false)
     */
    private $iswebserver;

    /**
     * @var \DateTime $lastcheck
     *
     * @ORM\Column(name="lastCheck", type="date", nullable=false)
     */
    private $lastcheck;



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
     * Set ip
     *
     * @param string $ip
     * @return Ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return Ip
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    
        return $this;
    }

    /**
     * Get hostname
     *
     * @return string 
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set iswebserver
     *
     * @param integer $iswebserver
     * @return Ip
     */
    public function setIswebserver($iswebserver)
    {
        $this->iswebserver = $iswebserver;
    
        return $this;
    }

    /**
     * Get iswebserver
     *
     * @return integer 
     */
    public function getIswebserver()
    {
        return $this->iswebserver;
    }

    /**
     * Set lastcheck
     *
     * @param \DateTime $lastcheck
     * @return Ip
     */
    public function setLastcheck($lastcheck)
    {
        $this->lastcheck = $lastcheck;
    
        return $this;
    }

    /**
     * Get lastcheck
     *
     * @return \DateTime 
     */
    public function getLastcheck()
    {
        return $this->lastcheck;
    }

    /**
     * Set as
     *
     * @param Mcc\ASMemberBundle\Entity\As $as
     * @return Ip
     */
    public function setAutonomousSytem(\Mcc\ASMemberBundle\Entity\AutonomousSytem $asidentifier = null)
    {
        $this->asidentifier = $asidentifier;
    
        return $this;
    }

    /**
     * Get as
     *
     * @return Mcc\ASMemberBundle\Entity\As 
     */
    public function getAutonomousSytem()
    {
        return $this->asidentifier;
    }
}