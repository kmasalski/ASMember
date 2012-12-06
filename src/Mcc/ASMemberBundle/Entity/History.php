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
     * @ORM\ManyToOne(targetEntity="File")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fileId", referencedColumnName="id")
     * })
     */
    private $fileId;

    /**
     * @var \DateTime $whenchecked
     *
     * @ORM\Column(name="whenChecked", type="date", nullable=false)
     */
    private $whenchecked;

    /**
     * @var integer $speedCurl
     *
     * @ORM\Column(name="speedCurl", type="integer", nullable=false)
     */
    private $speedCurl;

    /**
     * @var integer $speedObtained
     *
     * @ORM\Column(name="speedObtained", type="integer", nullable=true)
     */
    private $speedObtained;
    
    
    /**
     * @var integer $time
     *
     * @ORM\Column(name="time", type="integer", nullable=false)
     */
    private $time;


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
     * Set speedCurl
     *
     * @param integer $speedCurl
     * @return History
     */
    public function setSpeedCurl($speedCurl)
    {
        $this->speedCurl = $speedCurl;
    
        return $this;
    }

    /**
     * Get speedCurl
     *
     * @return integer 
     */
    public function getSpeedCurl()
    {
        return $this->speedCurl;
    }

    /**
     * Set speedObtained
     *
     * @param integer $speedObtained
     * @return History
     */
    public function setSpeedObtained($speedObtained)
    {
        $this->speedObtained = $speedObtained;
    
        return $this;
    }

    /**
     * Get speedObtained
     *
     * @return integer 
     */
    public function getSpeedObtained()
    {
        return $this->speedObtained;
    }

    /**
     * Set fileId
     *
     * @param \Mcc\ASMemberBundle\Entity\File $fileId
     * @return History
     */
    public function setFileId(\Mcc\ASMemberBundle\Entity\File $fileId = null)
    {
        $this->fileId = $fileId;
    
        return $this;
    }

    /**
     * Get fileId
     *
     * @return \Mcc\ASMemberBundle\Entity\File 
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return History
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }
}