<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Phase
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="phase")
 */
class Phase {

    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     *
     * @var string
     * 
     * @ORM\Column(length=50)
     */
    private $name;
    /**
     * @ORM\Column(type="string")
     */
    private $number;
    /**
     * @ORM\OneToOne(targetEntity="Phase")
     */
    private $nextPhase;
    /**
     * @ORM\ManyToOne(targetEntity="ActionDetails")
     */
    private $actionDetails;
    
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
     * Set name
     *
     * @param string $name
     * @return Phase
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nextPhase
     *
     * @param \Meldon\WotRBundle\Entity\Phase $nextPhase
     * @return Phase
     */
    public function setNextPhase(\Meldon\WotRBundle\Entity\Phase $nextPhase = null)
    {
        $this->nextPhase = $nextPhase;

        return $this;
    }

    /**
     * Get nextPhase
     *
     * @return \Meldon\WotRBundle\Entity\Phase 
     */
    public function getNextPhase()
    {
        return $this->nextPhase;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Phase
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }


    /**
     * Set actionDetails
     *
     * @param \Meldon\WotRBundle\Entity\ActionDetails $actionDetails
     * @return Phase
     */
    public function setActionDetails(\Meldon\WotRBundle\Entity\ActionDetails $actionDetails = null)
    {
        $this->actionDetails = $actionDetails;

        return $this;
    }

    /**
     * Get actionDetails
     *
     * @return \Meldon\WotRBundle\Entity\ActionDetails 
     */
    public function getActionDetails()
    {
        return $this->actionDetails;
    }
}
