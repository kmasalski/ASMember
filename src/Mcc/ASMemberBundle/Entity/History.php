<?php

namespace Mcc\ASMemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mcc\ASMemberBundle\Entity\History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity
 */
class History
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
     * @var Ip
     *
     * @ORM\ManyToOne(targetEntity="Ip")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ipid", referencedColumnName="id")
     * })
     */
    private $ipid;

    /**
     * @var \DateTime $whenchecked
     *
     * @ORM\Column(name="whenChecked", type="date", nullable=false)
     */
    private $whenchecked;

    /**
     * @var integer $iswebserver
     *
     * @ORM\Column(name="isWebServer", type="integer", nullable=false)
     */
    private $iswebserver;



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
     * Set whenchecked
     *
     * @param \DateTime $whenchecked
     * @return History
     */
    public function setWhenchecked($whenchecked)
    {
        $this->whenchecked = $whenchecked;
    
        return $this;
    }

    /**
     * Get whenchecked
     *
     * @return \DateTime 
     */
    public function getWhenchecked()
    {
        return $this->whenchecked;
    }

    /**
     * Set iswebserver
     *
     * @param integer $iswebserver
     * @return History
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
     * Set ipid
     *
     * @param Mcc\ASMemberBundle\Entity\Ip $ipid
     * @return History
     */
    public function setIpid(\Mcc\ASMemberBundle\Entity\Ip $ipid = null)
    {
        $this->ipid = $ipid;
    
        return $this;
    }

    /**
     * Get ipid
     *
     * @return Mcc\ASMemberBundle\Entity\Ip 
     */
    public function getIpid()
    {
        return $this->ipid;
    }
}