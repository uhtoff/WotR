<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Card
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="CardRepository")
 * @ORM\Table(name="wotrnew.card")
 */
class Card {
    use MagicDetails;
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
     * @ORM\ManyToOne(targetEntity="CardDetails", fetch="EAGER")
     */
    private $details;
    /**
     *
     * @ORM\ManyToOne(targetEntity="CardGroup",inversedBy="cards")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $group;
    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $position;
    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $faceup = false;
    public function __construct(CardDetails $details = NULL)
    {
        if ( $details ) $this->setDetails($details);
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
     * Set position
     *
     * @param integer $position
     * @return Card
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set group
     *
     * @param \Meldon\WotRBundle\Entity\CardGroup $group
     * @return Card
     */
    public function setGroup(\Meldon\WotRBundle\Entity\CardGroup $group = null)
    {
        if ( $this->group ) {
            $this->removeGroup();
        }
        $this->group = $group;
        $group->addCard($this);
        return $this;
    }
    
    public function removeGroup()
    {
        $this->getGroup()->removeCard($this);
        $this->group = NULL;
    }

    /**
     * Get group
     *
     * @return \Meldon\WotRBundle\Entity\CardGroup 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set faceup
     *
     * @param boolean $faceup
     * @return Card
     */
    public function setFaceup($faceup)
    {
        $this->faceup = $faceup;

        return $this;
    }

    /**
     * Get faceup
     *
     * @return boolean 
     */
    public function getFaceup()
    {
        return $this->faceup;
    }

    /**
     * Set details
     *
     * @param \Meldon\WotRBundle\Entity\CardDetails $details
     * @return Card
     */
    public function setDetails(\Meldon\WotRBundle\Entity\CardDetails $details = null)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return \Meldon\WotRBundle\Entity\CardDetails 
     */
    public function getDetails()
    {
        return $this->details;
    }
}
