<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Location
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="LocationRepository")
 * @ORM\Table(name="wotrnew.location")
 */
class Location {
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
     * @ORM\ManyToOne(targetEntity="LocationDetails", fetch="EAGER")
     */
    private $details;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="locations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    /**
     *
     * @ORM\OneToMany(targetEntity="Unit", mappedBy="location", cascade={"persist"})
     */
    private $units;
    /**
     *
     * @ORM\OneToMany(targetEntity="Connection", mappedBy="source", cascade={"persist"})
     */
    private $connections;

    public function __construct(LocationDetails $details = NULL, Game $game = NULL)
    {
        if ( $details ) $this->setDetails($details);
        if ( $game ) $this->setGame ($game);
        $this->units = new ArrayCollection();
        $this->connections = new ArrayCollection();
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
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return Location
     */
    public function setSide(\Meldon\WotRBundle\Entity\Side $side = null)
    {
        $this->side = $side;

        return $this;
    }

    /**
     * Get side
     *
     * @return \Meldon\WotRBundle\Entity\Side 
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return Location
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->addLocation($this);
        return $this;
    }

    /**
     * Get game
     *
     * @return \Meldon\WotRBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set details
     *
     * @param \Meldon\WotRBundle\Entity\LocationDetails $details
     * @return Location
     */
    public function setDetails(\Meldon\WotRBundle\Entity\LocationDetails $details = null)
    {
        $this->details = $details;
        if ( $details->getNation() )
        {
            $this->setSide($details->getNation()->getSide());
        }
        return $this;
    }

    /**
     * Get details
     *
     * @return \Meldon\WotRBundle\Entity\LocationDetails 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Add units
     *
     * @param \Meldon\WotRBundle\Entity\Unit $units
     * @return Location
     */
    public function addUnit(\Meldon\WotRBundle\Entity\Unit $units)
    {
        $this->units[] = $units;

        return $this;
    }

    /**
     * Remove units
     *
     * @param \Meldon\WotRBundle\Entity\Unit $units
     */
    public function removeUnit(\Meldon\WotRBundle\Entity\Unit $units)
    {
        $this->units->removeElement($units);
    }

    /**
     * Get units
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUnits()
    {
        return $this->units;
    }
    public function hasUnitsFromSide(Side $side) {
        if ( !$this->getUnits()->isEmpty() && $this->getUnits()->first()->getSide() == $side ) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Add connections
     *
     * @param \Meldon\WotRBundle\Entity\Connection $connections
     * @return Location
     */
    public function addConnection(\Meldon\WotRBundle\Entity\Connection $connections)
    {
        $this->connections[] = $connections;

        return $this;
    }

    /**
     * Remove connections
     *
     * @param \Meldon\WotRBundle\Entity\Connection $connections
     */
    public function removeConnection(\Meldon\WotRBundle\Entity\Connection $connections)
    {
        $this->connections->removeElement($connections);
    }

    /**
     * Get connections
     *
     * @return ArrayCollection
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * This is currently very inefficient and the exact clause doesn't work!
     * @param integer $depth
     * @param ArrayCollection $dests
     * @param bool $attack
     * @return ArrayCollection
     */
    public function getDestinations($depth = 1, $dests = NULL, $attack = false)
    {
        if ( !$dests )
        {
            $dests = new ArrayCollection();
            $dests->add($this);
        }
        if ( !$depth )
        {
            return $dests;
        } else            
        {
            foreach( $dests as $l )
            {
                foreach( $l->getConnections() as $c )
                {
                    if ( !$dests->contains($c->getDestination()) )
                    {
                        if ( !$c->getStronghold() || $attack ) {
                            $dests->add($c->getDestination());
                        }
                    }
                }
            }
            return $this->getDestinations(--$depth, $dests, $attack);
        }
        
    }
    public function findDestinations($depth, ArrayCollection $previous)
    {

    }

    /**
     * For display in list of locations for moving armies
     * @return string
     */
    public function getNameWithUnits() {
        $units = $this->getUnits();
        if ( $units->count() > 0 ) {
            $unitArr = array();
            foreach ($units as $u) {
                if (!array_key_exists($u->getName(), $unitArr)) {
                    $unitArr[$u->getName()] = 1;
                } else {
                    $unitArr[$u->getName()]++;
                }
            }
            $unitText = '';
            foreach ($unitArr as $k => $v) {
                $unitText .= $v . ' ' . $k . ', ';
            }
            $unitText = substr($unitText, 0, -2);
            return $this->getDetails()->getName() . ' (' . $unitText . ')';
        } else {
            return $this->getDetails()->getName();
        }
    }

    public function getNationDetails() {
        return $this->getDetails()->getNation();
    }

    /**
     * Return internal Stronghold location if exists, else false
     * @return bool|Location
     */
    public function getStrongholdConnection()
    {
        /** @var Connection $c */
        foreach( $this->getConnections() as $c ) {
            if ( $c->getStronghold() ) {
                return $c->getDestination();
            }
        }
        return false;
    }

    public function getAttackableLocs()
    {
        return $this->getDestinations(1, NULL, true);
    }
}
