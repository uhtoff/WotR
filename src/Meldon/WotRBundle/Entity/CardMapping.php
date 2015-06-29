<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of CardMapping
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.cardmapping")
 */
class CardMapping {
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
     * @ORM\ManyToOne(targetEntity="CardGroupDetails", inversedBy="mappings")
     */
    private $source;
    /**
     *
     * @ORM\ManyToOne(targetEntity="CardGroupDetails")
     */
    private $destination;
    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $default;
    /**
     *
     * @ORM\Column(length=100)
     */
    private $type;
    /**
     *
     * @ORM\Column(length=100)
     */
    private $identifier;

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
     * Set default
     *
     * @param boolean $default
     * @return CardMapping
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return boolean 
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return CardMapping
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set source
     *
     * @param \Meldon\WotRBundle\Entity\CardGroupDetails $source
     * @return CardMapping
     */
    public function setSource(\Meldon\WotRBundle\Entity\CardGroupDetails $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \Meldon\WotRBundle\Entity\CardGroupDetails 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set destination
     *
     * @param \Meldon\WotRBundle\Entity\CardGroupDetails $destination
     * @return CardMapping
     */
    public function setDestination(\Meldon\WotRBundle\Entity\CardGroupDetails $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return \Meldon\WotRBundle\Entity\CardGroupDetails 
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return CardMapping
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
