<?php

namespace Mcc\ASMemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\UniqueConstraints as uniqueConstraints;
//* @ORM\Table(name = "file", uniqueConstraints={@UniqueConstraint(name="address", columns={"address"})})

/**
 * File
 *
 * @ORM\Table(name = "file")
 * @ORM\Entity
 */
class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=1000)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;


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
     * Set address
     *
     * @param string $address
     * @return File
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
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
