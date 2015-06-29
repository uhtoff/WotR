<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BattleBackup
 */
class BattleBackup
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $round;

    /**
     * @var integer
     */
    private $attackerPreCombatRollHits;

    /**
     * @var integer
     */
    private $defenderPreCombatRollHits;

    /**
     * @var integer
     */
    private $attackerCombatRollHits;

    /**
     * @var integer
     */
    private $defenderCombatRollHits;

    /**
     * @var integer
     */
    private $attackerRerollHits;

    /**
     * @var integer
     */
    private $defenderRerollHits;

    /**
     * @var integer
     */
    private $attackerExtraHits;

    /**
     * @var integer
     */
    private $defenderExtraHits;

    /**
     * @var array
     */
    private $attackerCombatRoll;

    /**
     * @var array
     */
    private $defenderCombatRoll;

    /**
     * @var array
     */
    private $attackerReroll;

    /**
     * @var array
     */
    private $defenderReroll;

    /**
     * @var array
     */
    private $attackerPreCombatRoll;

    /**
     * @var array
     */
    private $defenderPreCombatRoll;

    /**
     * @var integer
     */
    private $attackerHitsToResolve;

    /**
     * @var integer
     */
    private $defenderHitsToResolve;

    /**
     * @var integer
     */
    private $attackerCombatModifier;

    /**
     * @var integer
     */
    private $defenderCombatModifier;

    /**
     * @var integer
     */
    private $attackerRerollModifier;

    /**
     * @var integer
     */
    private $defenderRerollModifier;

    /**
     * @var boolean
     */
    private $cardsRevealed;

    /**
     * @var integer
     */
    private $attackerStrength;

    /**
     * @var integer
     */
    private $defenderStrength;

    /**
     * @var integer
     */
    private $attackerLeadership;

    /**
     * @var integer
     */
    private $defenderLeadership;

    /**
     * @var integer
     */
    private $nazgulLeadership;

    /**
     * @var \Meldon\WotRBundle\Entity\Game
     */
    private $game;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $units;

    /**
     * @var \Meldon\WotRBundle\Entity\Combatant
     */
    private $attacker;

    /**
     * @var \Meldon\WotRBundle\Entity\Combatant
     */
    private $defender;

    /**
     * @var \Meldon\WotRBundle\Entity\Location
     */
    private $attackerLocation;

    /**
     * @var \Meldon\WotRBundle\Entity\Location
     */
    private $defenderLocation;

    /**
     * @var \Meldon\WotRBundle\Entity\Card
     */
    private $attackerCard;

    /**
     * @var \Meldon\WotRBundle\Entity\Card
     */
    private $defenderCard;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->units = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return BattleBackup
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
     * Set attackerPreCombatRollHits
     *
     * @param integer $attackerPreCombatRollHits
     * @return BattleBackup
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
     * @return BattleBackup
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
     * Set attackerCombatRollHits
     *
     * @param integer $attackerCombatRollHits
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * Set attackerExtraHits
     *
     * @param integer $attackerExtraHits
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * Set cardsRevealed
     *
     * @param boolean $cardsRevealed
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return BattleBackup
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
     * Add units
     *
     * @param \Meldon\WotRBundle\Entity\Unit $units
     * @return BattleBackup
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
     * @param \Meldon\WotRBundle\Entity\Combatant $attacker
     * @return BattleBackup
     */
    public function setAttacker(\Meldon\WotRBundle\Entity\Combatant $attacker = null)
    {
        $this->attacker = $attacker;

        return $this;
    }

    /**
     * Get attacker
     *
     * @return \Meldon\WotRBundle\Entity\Combatant 
     */
    public function getAttacker()
    {
        return $this->attacker;
    }

    /**
     * Set defender
     *
     * @param \Meldon\WotRBundle\Entity\Combatant $defender
     * @return BattleBackup
     */
    public function setDefender(\Meldon\WotRBundle\Entity\Combatant $defender = null)
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * Get defender
     *
     * @return \Meldon\WotRBundle\Entity\Combatant 
     */
    public function getDefender()
    {
        return $this->defender;
    }

    /**
     * Set attackerLocation
     *
     * @param \Meldon\WotRBundle\Entity\Location $attackerLocation
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
     * @return BattleBackup
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
}
