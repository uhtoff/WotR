<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Connection
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.connection")
 */
class Connection {

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
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="connections", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $source;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Location", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $destination;
    /**
     * @ORM\Column(type="boolean")
     */
    private $stronghold;

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
     * Set source
     *
     * @param \Meldon\WotRBundle\Entity\Location $source
     * @return Connection
     */
    public function setSource(\Meldon\WotRBundle\Entity\Location $source = null)
    {
        $this->source = $source;
        $source->addConnection($this);
        return $this;
    }

    /**
     * Get source
     *
     * @return \Meldon\WotRBundle\Entity\Location 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set destination
     *
     * @param \Meldon\WotRBundle\Entity\Location $destination
     * @return Connection
     */
    public function setDestination(\Meldon\WotRBundle\Entity\Location $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return \Meldon\WotRBundle\Entity\Location 
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set stronghold
     *
     * @param boolean $stronghold
     * @return Connection
     */
    public function setStronghold($stronghold)
    {
        $this->stronghold = $stronghold;

        return $this;
    }

    /**
     * Get stronghold
     *
     * @return boolean 
     */
    public function getStronghold()
    {
        return $this->stronghold;
    }
}
