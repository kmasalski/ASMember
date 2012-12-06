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
     * @ORM\Column(name="speedObtained", type="integer", nullable=false)
     */
    private $speedObtained;

}