<?php

namespace Mcc\ASMemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mcc\ASMemberBundle\Entity\IpRange
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class IpRange
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var AutonomousSystem
     *
     * @ORM\ManyToOne(targetEntity="AutonomousSystem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="asid", referencedColumnName="id")
     * })
     */
    private $asid;

    /**
     * @var string $range
     *
     * @ORM\Column(name="iprange", type="string", length=255)
     */
    private $IpRange;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="dateCheck", type="datetime")
     */
    private $dateCheck;


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
     * Set asid
     *
     * @param integer $asid
     * @return Range
     */
    public function setAsid($asid)
    {
        $this->asid = $asid;
    
        return $this;
    }

    /**
     * Get asid
     *
     * @return integer 
     */
    public function getAsid()
    {
        return $this->asid;
    }

    /**
     * Set range
     *
     * @param string $IpRange
     * @return Range
     */
    public function setIpRange($IpRange)
    {
        $this->IpRange = $IpRange;
    
        return $this;
    }

    /**
     * Get IpRange
     *
     * @return string 
     */
    public function getIpRangee()
    {
        return $this->IpRange;
    }

    /**
     * Set dateCheck
     *
     * @param \DateTime $dateCheck
     * @return Range
     */
    public function setDateCheck($dateCheck)
    {
        $this->dateCheck = $dateCheck;
    
        return $this;
    }

    /**
     * Get dateCheck
     *
     * @return \DateTime 
     */
    public function getDateCheck()
    {
        return $this->dateCheck;
    }
    
        public function __toString()
    {
       return $this->IpRange;
    }
}
