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
    
    //kod własny
    public function download()
    {
        $ch = curl_init($this->address);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Sitepoint Examples (thread 581410; http://www.sitepoint.com/forums/showthread.php?t=581410)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        set_time_limit(65);

        curl_exec($ch);
        $info = curl_getinfo($ch);

        // Time spent downloading, I think
        $time = $info['total_time'];
        
        $statistics['size_download'] = $info['size_download'];
        $statistics['time'] = $time;
        $statistics['speedCurl'] = $info['speed_download'] * 8 / 1024 / 1024;
        if($time != 0)
        {
            $statistics['speedObtained'] = $info['size_download'] * 8 / $time / 1024 / 1024;
        }
        else
        {
            $statistics['speedObtained'] = null;
        }
        
        unset($ch);
        //var_dump($statistics);
        return $statistics;
    }
    
}
