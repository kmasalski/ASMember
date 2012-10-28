<?php

namespace Mcc\ASMemberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mcc\ASMemberBundle\Entity\AutonomousSystem
 *
 * @ORM\Table(name="Autonomoussystem")
 * @ORM\Entity
 */
class AutonomousSystem
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
     * @var string $asidentifier
     *
     * @ORM\Column(name="ASIdentifier", type="string", length=15, nullable=false)
     */
    private $asidentifier;

    /**
     * @var string $asname
     *
     * @ORM\Column(name="ASName", type="string", length=255, nullable=true)
     */
    private $asname;



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
     * Set asidentifier
     *
     * @param string $asidentifier
     * @return AutonomousSystem
     */
    public function setAs($asidentifier)
    {
        $this->asidentifier = $asidentifier;
    
        return $this;
    }

    /**
     * Get asidentifier
     *
     * @return string 
     */
    public function getAsIdentifier()
    {
        return $this->asidentifier;
    }

    /**
     * Set asname
     *
     * @param string $asname
     * @return AutonomousSystem
     */
    public function setAsname($asname)
    {
        $this->asname = $asname;
    
        return $this;
    }

    /**
     * Get asname
     *
     * @return string 
     */
    public function getAsname()
    {
        return $this->asname;
    }
}