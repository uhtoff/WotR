<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Meldon\WotRBundle\Factory\LogFactory;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Description of Unit
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="UnitRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\Table(name="wotrnew.unit")
 */
class Unit {
    const REGULAR = 1;
    const ELITE = 2;
    const LEADER = 3;
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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="units")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    /**
     * @ORM\ManyToOne(targetEntity="Nation", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $nation;
    /**
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     * @var UnitType
     * @ORM\ManyToOne(targetEntity="UnitType")
     */
    private $type;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="units")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $location;
    /**
     *
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $casualty = false;
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lastMoved;
    /**
     * @ORM\ManyToOne(targetEntity="Combatant", inversedBy="units")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $combatant;

    public function __construct(Nation $nation = NULL, UnitType $type = NULL, Location $location = NULL, Game $game = NULL)
    {
        if ( $nation ) $this->setNation($nation);
        if ( $type ) $this->setType($type);
        if ( $location ) $this->setLocation($location);
        if ( $game ) $this->setGame($game);
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
     * Set nation
     *
     * @param \Meldon\WotRBundle\Entity\Nation $nation
     * @return Unit
     */
    public function setNation(\Meldon\WotRBundle\Entity\Nation $nation = null)
    {
        $this->nation = $nation;
        $this->setSide($nation->getSide());
        return $this;
    }

    /**
     * Get nation
     *
     * @return \Meldon\WotRBundle\Entity\Nation 
     */
    public function getNation()
    {
        return $this->nation;
    }

    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return Unit
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
     * Set type
     *
     * @param \Meldon\WotRBundle\Entity\UnitType $type
     * @return Unit
     */
    public function setType(\Meldon\WotRBundle\Entity\UnitType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Meldon\WotRBundle\Entity\UnitType 
     */
    public function getType()
    {
        return $this->type;
    }
    
    public function getName()
    {
        if ( $this->getNation()->getName() === 'Sauron' && $this->getType()->getName() === 'Leader' ) {
            return 'Nazgûl';
        } else {
            return $this->getNation()->getAdjective() . ' ' . $this->getType()->getName();
        }
    }

    /**
     * Set casualty
     *
     * @param boolean $casualty
     * @return Unit
     */
    public function setCasualty($casualty)
    {
        $this->casualty = $casualty;

        return $this;
    }

    /**
     * Get casualty
     *
     * @return boolean 
     */
    public function getCasualty()
    {
        return $this->casualty;
    }

    /**
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return Unit
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->addUnit($this);
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
     * Set location
     *
     * @param \Meldon\WotRBundle\Entity\Location $location
     * @return Unit
     */
    public function setLocation(\Meldon\WotRBundle\Entity\Location $location = null)
    {
        if ( is_a($this->getLocation(),'Location') )
        {
            $this->getLocation()->removeUnit($this);
        }
        $this->location = $location;
        $location->addUnit($this);
        return $this;
    }

    /**
     * Get location
     *
     * @return \Meldon\WotRBundle\Entity\Location 
     */
    public function getLocation()
    {
        return $this->location;
    }

    public function removeLocation()
    {
        $this->getLocation()->removeUnit($this);
        $this->location = NULL;
    }

    /**
     * Set lastMoved
     *
     * @param integer $lastMoved
     * @return Unit
     */
    public function setLastMoved($lastMoved)
    {
        $this->lastMoved = $lastMoved;

        return $this;
    }

    /**
     * Get lastMoved
     *
     * @return integer 
     */
    public function getLastMoved()
    {
        return $this->lastMoved;
    }

    public function isNationAtWar() {
        $n = $this->getNation();
        if ( $n )
            return $n->atWar();
    }

    public function validateIsNationAtWar(ExecutionContextInterface $context) {
        if ( !$this->isNationAtWar() ) {
            $context->buildViolation('The unit\'s nation must be at War.')
                ->atPath('units')
                ->addViolation();
        }
    }

    public function getStrength()
    {
        return $this->getType()->getStrength();
    }

    public function getLeadership()
    {
        return $this->getType()->getLeadership();
    }

    public function isNazgul()
    {
        return $this->getName() === 'Nazgûl';
    }


    /**
     * Set combatant
     *
     * @param \Meldon\WotRBundle\Entity\Combatant $combatant
     * @return Unit
     */
    public function setCombatant(\Meldon\WotRBundle\Entity\Combatant $combatant = null)
    {
        $this->combatant = $combatant;
        $combatant->addUnit($this);
        return $this;
    }

    /**
     * Get combatant
     *
     * @return \Meldon\WotRBundle\Entity\Combatant 
     */
    public function getCombatant()
    {
        return $this->combatant;
    }

    public function removeCombatant()
    {
        $this->combatant = NULL;
    }

    public function takeHit(Game $game)
    {
        if ( $this->isLeader($game) || $this->isCharacter() ) {
            return false;
        } elseif ( $this->isElite() ) {
            $game->reduceElite($this);
            $this->becomeCasualty();
            LogFactory::setText($this->getName() . ' has been reduced to a regular.');
        } else {
            $this->becomeCasualty();
            LogFactory::setText($this->getName() . ' has been taken as a casualty.');
        }
        return 1;
    }

    /**
     * Take a unit off the board as a casualty - always return to reinforcements if $casualty is false
     * @param bool $casualty
     */
    public function becomeCasualty($casualty = true)
    {
        if ( $this->getLocation() ) {
            $this->removeLocation();
        }
        $this->removeCombatant();
        if ( $casualty && $this->getSide()->isFP() ) {
            $this->setCasualty(true);
        }
    }

    public function isRegular()
    {
        return $this->getType()->getId() == self::REGULAR;
    }

    public function isElite()
    {
        return $this->getType()->getId() == self::ELITE;
    }

    /**
     * Take game object as some game state can change if a unit is a leader (e.g. Saruman being in play)
     * @param Game $game
     * @return bool
     */
    public function isLeader(Game $game)
    {
        return $this->getType()->getId() == self::LEADER;
    }


    public function isCharacter()
    {
        return false;
    }

    public function getHits()
    {
        return $this->getType()->getHits();
    }

    public function getNameWithLocation()
    {
        return "{$this->getName()} ({$this->getLocation()->getName()})";
    }
}
