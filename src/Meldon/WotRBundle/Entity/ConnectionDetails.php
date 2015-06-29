<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ConnectionDetails
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="connectiondetails")
 */
class ConnectionDetails {

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
     * @ORM\ManyToOne(targetEntity="LocationDetails", inversedBy="connections")
     */
    private $source;
    /**
     *
     * @ORM\ManyToOne(targetEntity="LocationDetails")
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
     * @param \Meldon\WotRBundle\Entity\LocationDetails $source
     * @return ConnectionDetails
     */
    public function setSource(\Meldon\WotRBundle\Entity\LocationDetails $source = null)
    {
        $this->source = $source;
        $source->addConnection($this);
        return $this;
    }

    /**
     * Get source
     *
     * @return \Meldon\WotRBundle\Entity\LocationDetails 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set destination
     *
     * @param \Meldon\WotRBundle\Entity\LocationDetails $destination
     * @return ConnectionDetails
     */
    public function setDestination(\Meldon\WotRBundle\Entity\LocationDetails $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return \Meldon\WotRBundle\Entity\LocationDetails 
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set stronghold
     *
     * @param boolean $stronghold
     * @return ConnectionDetails
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
