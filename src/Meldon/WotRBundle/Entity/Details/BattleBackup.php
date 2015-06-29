<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 01/06/2015
 * Time: 22:42
 */

namespace Meldon\WotRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Battle
 *
 * @author Russ
 */
class BattleBackup {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Game", mappedBy="battle")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    /**
     * @ORM\OneToMany(targetEntity="Unit", mappedBy="battle")
     */
    private $units;
    /**
     * @ORM\OneToOne(targetEntity="Combatant", orphanRemoval=true)
     */
    private $attacker;
    /**
     * @ORM\OneToOne(targetEntity="Combatant", orphanRemoval=true)
     */
    private $defender;
    /**
     * @ORM\ManyToOne(targetEntity="Location")
     */
    private $attackerLocation;
    /**
     * @ORM\ManyToOne(targetEntity="Location")
     */
    private $defenderLocation;
    /**
     * @ORM\Column(type="integer")
     */
    private $round = 1;
    /**
     * @ORM\ManyToOne(targetEntity="Card")
     */
    private $attackerCard;
    /**
     * @ORM\ManyToOne(targetEntity="Card")
     */
    private $defenderCard;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $attackerPreCombatRollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $defenderPreCombatRollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $attackerCombatRollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $defenderCombatRollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $attackerRerollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $defenderRerollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $attackerExtraHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $defenderExtraHits;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $attackerCombatRoll;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $defenderCombatRoll;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $attackerReroll;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $defenderReroll;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $attackerPreCombatRoll;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $defenderPreCombatRoll;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $attackerHitsToResolve;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $defenderHitsToResolve;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $attackerCombatModifier;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $defenderCombatModifier;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $attackerRerollModifier;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $defenderRerollModifier;
    /**
     * @ORM\Column(type="boolean")
     */
    private $cardsRevealed = false;
    /**
     * @ORM\Column(type="integer")
     */
    private $attackerStrength;
    /**
     * @ORM\Column(type="integer")
     */
    private $defenderStrength;
    /**
     * @ORM\Column(type="integer")
     */
    private $attackerLeadership;
    /**
     * @ORM\Column(type="integer")
     */
    private $defenderLeadership;
    /**
     * @ORM\Column(type="integer")
     */
    private $nazgulLeadership;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->units = new ArrayCollection();
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
     * Set round
     *
     * @param integer $round
     * @return Battle
     */
    public function setRound($round)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return integer 
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Set attackerCombatRollHits
     *
     * @param integer $attackerCombatRollHits
     * @return Battle
     */
    public function setAttackerCombatRollHits($attackerCombatRollHits)
    {
        $this->attackerCombatRollHits = $attackerCombatRollHits;

        return $this;
    }

    /**
     * Get attackerCombatRollHits
     *
     * @return integer 
     */
    public function getAttackerCombatRollHits()
    {
        return $this->attackerCombatRollHits;
    }

    /**
     * Set defenderCombatRollHits
     *
     * @param integer $defenderCombatRollHits
     * @return Battle
     */
    public function setDefenderCombatRollHits($defenderCombatRollHits)
    {
        $this->defenderCombatRollHits = $defenderCombatRollHits;

        return $this;
    }

    /**
     * Get defenderCombatRollHits
     *
     * @return integer 
     */
    public function getDefenderCombatRollHits()
    {
        return $this->defenderCombatRollHits;
    }

    /**
     * Set attackerRerollHits
     *
     * @param integer $attackerRerollHits
     * @return Battle
     */
    public function setAttackerRerollHits($attackerRerollHits)
    {
        $this->attackerRerollHits = $attackerRerollHits;

        return $this;
    }

    /**
     * Get attackerRerollHits
     *
     * @return integer 
     */
    public function getAttackerRerollHits()
    {
        return $this->attackerRerollHits;
    }

    /**
     * Set defenderRerollHits
     *
     * @param integer $defenderRerollHits
     * @return Battle
     */
    public function setDefenderRerollHits($defenderRerollHits)
    {
        $this->defenderRerollHits = $defenderRerollHits;

        return $this;
    }

    /**
     * Get defenderRerollHits
     *
     * @return integer 
     */
    public function getDefenderRerollHits()
    {
        return $this->defenderRerollHits;
    }

    /**
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return Battle
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
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
     * Clear game
     */
    public function removeGame()
    {
        $this->getGame()->removeBattle();
    }

    /**
     * Add units
     *
     * @param \Meldon\WotRBundle\Entity\Unit $units
     * @return Battle
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

    /**
     * Set attacker
     *
     * @param \Meldon\WotRBundle\Entity\Side $attacker
     * @return Battle
     */
    public function setAttacker(\Meldon\WotRBundle\Entity\Side $attacker = null)
    {
        $this->attacker = $attacker;

        return $this;
    }

    /**
     * Get attacker
     *
     * @return \Meldon\WotRBundle\Entity\Side 
     */
    public function getAttacker()
    {
        return $this->attacker;
    }

    /**
     * Set defender
     *
     * @param \Meldon\WotRBundle\Entity\Side $defender
     * @return Battle
     */
    public function setDefender(\Meldon\WotRBundle\Entity\Side $defender = null)
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * Get defender
     *
     * @return \Meldon\WotRBundle\Entity\Side 
     */
    public function getDefender()
    {
        return $this->defender;
    }

    /**
     * Set attackerLocation
     *
     * @param \Meldon\WotRBundle\Entity\Location $attackerLocation
     * @return Battle
     */
    public function setAttackerLocation(\Meldon\WotRBundle\Entity\Location $attackerLocation = null)
    {
        $this->attackerLocation = $attackerLocation;

        return $this;
    }

    /**
     * Get attackerLocation
     *
     * @return \Meldon\WotRBundle\Entity\Location 
     */
    public function getAttackerLocation()
    {
        return $this->attackerLocation;
    }

    /**
     * Set defenderLocation
     *
     * @param \Meldon\WotRBundle\Entity\Location $defenderLocation
     * @return Battle
     */
    public function setDefenderLocation(\Meldon\WotRBundle\Entity\Location $defenderLocation = null)
    {
        $this->defenderLocation = $defenderLocation;

        return $this;
    }

    /**
     * Get defenderLocation
     *
     * @return \Meldon\WotRBundle\Entity\Location 
     */
    public function getDefenderLocation()
    {
        return $this->defenderLocation;
    }

    /**
     * Set attackerCard
     *
     * @param \Meldon\WotRBundle\Entity\Card $attackerCard
     * @return Battle
     */
    public function setAttackerCard(\Meldon\WotRBundle\Entity\Card $attackerCard = null)
    {
        $this->attackerCard = $attackerCard;

        return $this;
    }

    /**
     * Get attackerCard
     *
     * @return \Meldon\WotRBundle\Entity\Card 
     */
    public function getAttackerCard()
    {
        return $this->attackerCard;
    }

    /**
     * Set defenderCard
     *
     * @param \Meldon\WotRBundle\Entity\Card $defenderCard
     * @return Battle
     */
    public function setDefenderCard(\Meldon\WotRBundle\Entity\Card $defenderCard = null)
    {
        $this->defenderCard = $defenderCard;

        return $this;
    }

    /**
     * Get defenderCard
     *
     * @return \Meldon\WotRBundle\Entity\Card 
     */
    public function getDefenderCard()
    {
        return $this->defenderCard;
    }

    public function getDefendingUnits()
    {
        $defSide = $this->getDefender();
        return $this->getUnits()->filter(
            function ($u) use ($defSide) {
                return $u->getSide() == $defSide;
            }
        );
    }

    public function getAttackingUnits()
    {
        $attSide = $this->getAttacker();
        return $this->getUnits()->filter(
            function ($u) use ($attSide) {
                return $u->getSide() == $attSide;
            }
        );
    }

    /**
     * Set cardsRevealed
     *
     * @param boolean $cardsRevealed
     * @return Battle
     */
    public function setCardsRevealed($cardsRevealed)
    {
        $this->cardsRevealed = $cardsRevealed;

        return $this;
    }

    /**
     * Get cardsRevealed
     *
     * @return boolean 
     */
    public function getCardsRevealed()
    {
        return $this->cardsRevealed;
    }

    /**
     * Set attackerStrength
     *
     * @param integer $attackerStrength
     * @return Battle
     */
    public function setAttackerStrength($attackerStrength)
    {
        $this->attackerStrength = $attackerStrength;

        return $this;
    }

    /**
     * Get attackerStrength
     *
     * @return integer 
     */
    public function getAttackerStrength()
    {
        return $this->attackerStrength;
    }

    /**
     * Set defenderStrength
     *
     * @param integer $defenderStrength
     * @return Battle
     */
    public function setDefenderStrength($defenderStrength)
    {
        $this->defenderStrength = $defenderStrength;

        return $this;
    }

    /**
     * Get defenderStrength
     *
     * @return integer 
     */
    public function getDefenderStrength()
    {
        return $this->defenderStrength;
    }

    /**
     * Set attackerLeadership
     *
     * @param integer $attackerLeadership
     * @return Battle
     */
    public function setAttackerLeadership($attackerLeadership)
    {
        $this->attackerLeadership = $attackerLeadership;

        return $this;
    }

    /**
     * Get attackerLeadership
     *
     * @return integer 
     */
    public function getAttackerLeadership()
    {
        return $this->attackerLeadership;
    }

    /**
     * Set defenderLeadership
     *
     * @param integer $defenderLeadership
     * @return Battle
     */
    public function setDefenderLeadership($defenderLeadership)
    {
        $this->defenderLeadership = $defenderLeadership;

        return $this;
    }

    /**
     * Get defenderLeadership
     *
     * @return integer 
     */
    public function getDefenderLeadership()
    {
        return $this->defenderLeadership;
    }

    /**
     * Set nazgulLeadership
     *
     * @param integer $nazgulLeadership
     * @return Battle
     */
    public function setNazgulLeadership($nazgulLeadership)
    {
        $this->nazgulLeadership = $nazgulLeadership;

        return $this;
    }

    /**
     * Get nazgulLeadership
     *
     * @return integer 
     */
    public function getNazgulLeadership()
    {
        return $this->nazgulLeadership;
    }

    /**
     * Set attackerPreCombatRollHits
     *
     * @param integer $attackerPreCombatRollHits
     * @return Battle
     */
    public function setAttackerPreCombatRollHits($attackerPreCombatRollHits)
    {
        $this->attackerPreCombatRollHits = $attackerPreCombatRollHits;

        return $this;
    }

    /**
     * Get attackerPreCombatRollHits
     *
     * @return integer 
     */
    public function getAttackerPreCombatRollHits()
    {
        return $this->attackerPreCombatRollHits;
    }

    /**
     * Set defenderPreCombatRollHits
     *
     * @param integer $defenderPreCombatRollHits
     * @return Battle
     */
    public function setDefenderPreCombatRollHits($defenderPreCombatRollHits)
    {
        $this->defenderPreCombatRollHits = $defenderPreCombatRollHits;

        return $this;
    }

    /**
     * Get defenderPreCombatRollHits
     *
     * @return integer 
     */
    public function getDefenderPreCombatRollHits()
    {
        return $this->defenderPreCombatRollHits;
    }

    /**
     * Set attackerExtraHits
     *
     * @param integer $attackerExtraHits
     * @return Battle
     */
    public function setAttackerExtraHits($attackerExtraHits)
    {
        $this->attackerExtraHits = $attackerExtraHits;

        return $this;
    }

    /**
     * Get attackerExtraHits
     *
     * @return integer 
     */
    public function getAttackerExtraHits()
    {
        return $this->attackerExtraHits;
    }

    /**
     * Set defenderExtraHits
     *
     * @param integer $defenderExtraHits
     * @return Battle
     */
    public function setDefenderExtraHits($defenderExtraHits)
    {
        $this->defenderExtraHits = $defenderExtraHits;

        return $this;
    }

    /**
     * Get defenderExtraHits
     *
     * @return integer 
     */
    public function getDefenderExtraHits()
    {
        return $this->defenderExtraHits;
    }

    /**
     * Set attackerCombatRoll
     *
     * @param array $attackerCombatRoll
     * @return Battle
     */
    public function setAttackerCombatRoll($attackerCombatRoll)
    {
        $this->attackerCombatRoll = $attackerCombatRoll;

        return $this;
    }

    /**
     * Get attackerCombatRoll
     *
     * @return array 
     */
    public function getAttackerCombatRoll()
    {
        return $this->attackerCombatRoll;
    }

    /**
     * Set defenderCombatRoll
     *
     * @param array $defenderCombatRoll
     * @return Battle
     */
    public function setDefenderCombatRoll($defenderCombatRoll)
    {
        $this->defenderCombatRoll = $defenderCombatRoll;

        return $this;
    }

    /**
     * Get defenderCombatRoll
     *
     * @return array 
     */
    public function getDefenderCombatRoll()
    {
        return $this->defenderCombatRoll;
    }

    /**
     * Set attackerReroll
     *
     * @param array $attackerReroll
     * @return Battle
     */
    public function setAttackerReroll($attackerReroll)
    {
        $this->attackerReroll = $attackerReroll;

        return $this;
    }

    /**
     * Get attackerReroll
     *
     * @return array 
     */
    public function getAttackerReroll()
    {
        return $this->attackerReroll;
    }

    /**
     * Set defenderReroll
     *
     * @param array $defenderReroll
     * @return Battle
     */
    public function setDefenderReroll($defenderReroll)
    {
        $this->defenderReroll = $defenderReroll;

        return $this;
    }

    /**
     * Get defenderReroll
     *
     * @return array 
     */
    public function getDefenderReroll()
    {
        return $this->defenderReroll;
    }

    /**
     * Set attackerPreCombatRoll
     *
     * @param array $attackerPreCombatRoll
     * @return Battle
     */
    public function setAttackerPreCombatRoll($attackerPreCombatRoll)
    {
        $this->attackerPreCombatRoll = $attackerPreCombatRoll;

        return $this;
    }

    /**
     * Get attackerPreCombatRoll
     *
     * @return array 
     */
    public function getAttackerPreCombatRoll()
    {
        return $this->attackerPreCombatRoll;
    }

    /**
     * Set defenderPreCombatRoll
     *
     * @param array $defenderPreCombatRoll
     * @return Battle
     */
    public function setDefenderPreCombatRoll($defenderPreCombatRoll)
    {
        $this->defenderPreCombatRoll = $defenderPreCombatRoll;

        return $this;
    }

    /**
     * Get defenderPreCombatRoll
     *
     * @return array 
     */
    public function getDefenderPreCombatRoll()
    {
        return $this->defenderPreCombatRoll;
    }

    /**
     * Set attackerHitsToResolve
     *
     * @param integer $attackerHitsToResolve
     * @return Battle
     */
    public function setAttackerHitsToResolve($attackerHitsToResolve)
    {
        $this->attackerHitsToResolve = $attackerHitsToResolve;

        return $this;
    }

    /**
     * Get attackerHitsToResolve
     *
     * @return integer 
     */
    public function getAttackerHitsToResolve()
    {
        return $this->attackerHitsToResolve;
    }

    /**
     * Set defenderHitsToResolve
     *
     * @param integer $defenderHitsToResolve
     * @return Battle
     */
    public function setDefenderHitsToResolve($defenderHitsToResolve)
    {
        $this->defenderHitsToResolve = $defenderHitsToResolve;

        return $this;
    }

    /**
     * Get defenderHitsToResolve
     *
     * @return integer 
     */
    public function getDefenderHitsToResolve()
    {
        return $this->defenderHitsToResolve;
    }

    /**
     * Set attackerCombatModifier
     *
     * @param integer $attackerCombatModifier
     * @return Battle
     */
    public function setAttackerCombatModifier($attackerCombatModifier)
    {
        $this->attackerCombatModifier = $attackerCombatModifier;

        return $this;
    }

    /**
     * Get attackerCombatModifier
     *
     * @return integer 
     */
    public function getAttackerCombatModifier()
    {
        return $this->attackerCombatModifier;
    }

    /**
     * Set defenderCombatModifier
     *
     * @param integer $defenderCombatModifier
     * @return Battle
     */
    public function setDefenderCombatModifier($defenderCombatModifier)
    {
        $this->defenderCombatModifier = $defenderCombatModifier;

        return $this;
    }

    /**
     * Get defenderCombatModifier
     *
     * @return integer 
     */
    public function getDefenderCombatModifier()
    {
        return $this->defenderCombatModifier;
    }

    /**
     * Set attackerRerollModifier
     *
     * @param integer $attackerRerollModifier
     * @return Battle
     */
    public function setAttackerRerollModifier($attackerRerollModifier)
    {
        $this->attackerRerollModifier = $attackerRerollModifier;

        return $this;
    }

    /**
     * Get attackerRerollModifier
     *
     * @return integer 
     */
    public function getAttackerRerollModifier()
    {
        return $this->attackerRerollModifier;
    }

    /**
     * Set defenderRerollModifier
     *
     * @param integer $defenderRerollModifier
     * @return Battle
     */
    public function setDefenderRerollModifier($defenderRerollModifier)
    {
        $this->defenderRerollModifier = $defenderRerollModifier;

        return $this;
    }

    /**
     * Get defenderRerollModifier
     *
     * @return integer 
     */
    public function getDefenderRerollModifier()
    {
        return $this->defenderRerollModifier;
    }

    /**
     * Cleanup function for when the battle ends, ensure units are removed and battle prepared for orphaning
     */
    public function cleanup()
    {
        foreach( $this->getUnits() as $unit ) {
            /** @var Unit $unit */
            $unit->removeBattle();
        }
        $this->removeGame();
        // Discard cards - though probably do this earlier
    }

    /**
     * Move all specified units to the specified location
     * @param ArrayCollection $units
     * @param Location $l
     */
    public function moveUnits(ArrayCollection $units, Location $l)
    {
        /** @var Unit $u */
        foreach ( $units as $u ) {
            $u->setLocation($l);
        }
    }

    /**
     * Move attackers to defenders location
     */
    public function advanceAttackers()
    {
        $this->moveUnits($this->getAttackingUnits(), $this->getDefenderLocation());
    }

    /**
     * Remove Stronghold excess - not marked as casualties regardless
     */
    public function removeOutsideDefenders()
    {
        $dL = $this->getDefenderLocation();
        $units = $this->getDefendingUnits()->filter(
            function ($u) use ($dL) {
                /** @var Unit $u */
                return $u->getLocation() == $dL;
            }
        );
        $this->removeFromBoard($units);
    }

    /**
     * Remove from board - if casualty set to true then mark as a casualty
     * @param ArrayCollection $units
     * @param bool $casualty
     */
    private function removeFromBoard(ArrayCollection $units, $casualty = false)
    {
        /** @var Unit $u */
        foreach( $units as $u ) {
            $u->setLocation(NULL);
            $u->removeBattle();
            if ( $casualty ) {
                $u->setCasualty(true);
            }
        }
    }

    /**
     * Set the combat values to the basic levels for the Combat Round
     */
    public function setCombatValues()
    {
        $aS = $aL = $dS = $dL = $nL = 0;
        /** @var Unit $u */
        foreach( $this->getAttackingUnits() as $u ) {
            $aS += $u->getStrength();
            $aL += $u->getLeadership();
            if ( $u->isNazgul() ) {
                $nL += $u->getLeadership();
            }
        }
        /** @var Unit $u */
        foreach( $this->getDefendingUnits() as $u ) {
            $dS += $u->getStrength();
            $dL += $u->getLeadership();
            if ( $u->isNazgul() ) {
                $nL += $u->getLeadership();
            }
        }
        $this->setAttackerStrength($aS);
        $this->setAttackerLeadership($aL);
        $this->setDefenderStrength($dS);
        $this->setDefenderLeadership($dL);
        $this->setNazgulLeadership($nL);
    }
}
