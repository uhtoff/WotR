<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of BaseLocation
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.locationdetails")
 */
class LocationDetails {
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
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $stronghold;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $town;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $city;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $fortifications;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $inStronghold;
    /**
     * @ORM\ManyToOne(targetEntity="NationDetails", fetch="EAGER")
     */
    private $nation;
    /**
     * @ORM\Column(type="boolean")
     */
    private $inMordor;
    /**
     *
     * @ORM\OneToMany(targetEntity="ConnectionDetails", mappedBy="source")
     */
    private $connections;
    /**
     * @ORM\Column(length=20)
     */
    private $unitPos;
    /**
     * @ORM\Column(type="text")
     */
    private $coords;

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
     * @return LocationDetails
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
     * Set stronghold
     *
     * @param boolean $stronghold
     * @return LocationDetails
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
    
    public function isStronghold()
    {
        return $this->stronghold;
    }

    /**
     * Set town
     *
     * @param boolean $town
     * @return LocationDetails
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return boolean 
     */
    public function getTown()
    {
        return $this->town;
    }
    
    public function isTown()
    {
        return $this->town;
    }

    /**
     * Set city
     *
     * @param boolean $city
     * @return LocationDetails
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return boolean 
     */
    public function getCity()
    {
        return $this->city;
    }

    public function isCity()
    {
        return $this->city;
    }
    /**
     * Set fortifications
     *
     * @param boolean $fortifications
     * @return LocationDetails
     */
    public function setFortifications($fortifications)
    {
        $this->fortifications = $fortifications;

        return $this;
    }

    /**
     * Get fortifications
     *
     * @return boolean 
     */
    public function getFortifications()
    {
        return $this->fortifications;
    }

    public function isFirstRoundDefence()
    {
        return $this->getFortifications() || $this->getCity();
    }
    /**
     * Set inStronghold
     *
     * @param boolean $inStronghold
     * @return LocationDetails
     */
    public function setInStronghold($inStronghold)
    {
        $this->inStronghold = $inStronghold;

        return $this;
    }

    /**
     * Get inStronghold
     *
     * @return boolean 
     */
    public function getInStronghold()
    {
        return $this->inStronghold;
    }

    public function isInStronghold()
    {
        return $this->inStronghold;
    }

    /**
     * Set nation
     *
     * @param \Meldon\WotRBundle\Entity\NationDetails $nation
     * @return LocationDetails
     */
    public function setNation(\Meldon\WotRBundle\Entity\NationDetails $nation = null)
    {
        $this->nation = $nation;

        return $this;
    }

    /**
     * Get nation
     *
     * @return \Meldon\WotRBundle\Entity\NationDetails 
     */
    public function getNation()
    {
        return $this->nation;
    }

    /**
     * Set inMordor
     *
     * @param boolean $inMordor
     * @return LocationDetails
     */
    public function setInMordor($inMordor)
    {
        $this->inMordor = $inMordor;

        return $this;
    }

    /**
     * Get inMordor
     *
     * @return boolean 
     */
    public function getInMordor()
    {
        return $this->inMordor;
    }
    public function isInMordor()
    {
        return $this->inMordor;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->connections = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add connections
     *
     * @param \Meldon\WotRBundle\Entity\ConnectionDetails $connections
     * @return LocationDetails
     */
    public function addConnection(\Meldon\WotRBundle\Entity\ConnectionDetails $connections)
    {
        $this->connections[] = $connections;

        return $this;
    }

    /**
     * Remove connections
     *
     * @param \Meldon\WotRBundle\Entity\ConnectionDetails $connections
     */
    public function removeConnection(\Meldon\WotRBundle\Entity\ConnectionDetails $connections)
    {
        $this->connections->removeElement($connections);
    }

    /**
     * Get connections
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConnections()
    {
        return $this->connections;
    }
    
    public function getDestinations()
    {
   
    }

    /**
     * Set unitPos
     *
     * @param string $unitPos
     * @return LocationDetails
     */
    public function setUnitPos($unitPos)
    {
        $this->unitPos = $unitPos;

        return $this;
    }

    /**
     * Get unitPos
     *
     * @return string 
     */
    public function getUnitPos()
    {
        return $this->unitPos;
    }

    /**
     * Set coords
     *
     * @param string $coords
     * @return LocationDetails
     */
    public function setCoords($coords)
    {
        $this->coords = $coords;

        return $this;
    }

    /**
     * Get coords
     *
     * @return string 
     */
    public function getCoords()
    {
        return $this->coords;
    }

    public function hasSettlement()
    {
        return !$this->isInStronghold() && ($this->getCity() || $this->getTown() || $this->getStronghold());
    }
}
