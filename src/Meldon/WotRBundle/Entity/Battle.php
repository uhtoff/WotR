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
use Meldon\WotRBundle\Factory\LogFactory;

/**
 * Description of Battle
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.battle")
 */
class Battle {
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
     * @ORM\OneToOne(targetEntity="Combatant", cascade={"persist"}, orphanRemoval=true)
     */
    private $attacker;
    /**
     * @ORM\OneToOne(targetEntity="Combatant", cascade={"persist"}, orphanRemoval=true)
     */
    private $defender;
    /**
     * @ORM\Column(type="integer")
     */
    private $round = 1;
    /**
     * @ORM\Column(type="boolean")
     */
    private $cardsRevealed = false;

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

    public function removeGame()
    {
        $this->getGame()->removeBattle();
    }

    /**
     * Set attacker
     *
     * @param \Meldon\WotRBundle\Entity\Combatant $attacker
     * @return Battle
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
     * @return Battle
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

    public function getOpponent(Side $s)
    {
        if ( $s->isShadow() ) {
            return $this->getFP();
        } else {
            return $this->getShadow();
        }
    }

    public function getCombatant(Side $s)
    {
        if ( $s->isShadow() ) {
            return $this->getShadow();
        } else {
            return $this->getFP();
        }
    }

    /**
     * Cleanup function for when the battle ends, ensure units are removed and battle prepared for orphaning
     */
    public function cleanup()
    {
        foreach( $this->getAttacker()->getUnits() as $unit ) {
            /** @var Unit $unit */
            $unit->removeCombatant();
        }
        foreach( $this->getDefender()->getUnits() as $unit ) {
            $unit->removeCombatant();
        }
        $this->setAttacker(NULL);
        $this->setDefender(NULL);
        $this->removeGame();
        // Discard cards - though probably do this earlier
    }

    /**
     * Remove Stronghold excess - not marked as casualties regardless
     */
    public function removeOutsideDefenders()
    {
        $dL = $this->getDefender()->getLocation();
        $units = $this->getDefender()->getUnits()->filter(
            function ($u) use ($dL) {
                /** @var Unit $u */
                return $u->getLocation() == $dL;
            }
        );
        $units->map(
            function ($u) {
                $u->becomeCasualty(false);
            }
        );
        LogFactory::setText("The units remaining outside the Stronghold disperse.");
    }

    /**
     * Set the combat values to the basic levels for the Combat Round
     */
    public function setCombatValues()
    {
        $this->getAttacker()->setCombatValues();
        $this->getDefender()->setCombatValues();
    }

    public function getAttackerCard()
    {
        return $this->getAttacker()->getCard();
    }

    public function getDefenderCard()
    {
        return $this->getDefender()->getCard();
    }

    public function getShadow()
    {
        if ( $this->getAttacker()->getSide()->isShadow() ) {
            return $this->getAttacker();
        } else {
            return $this->getDefender();
        }
    }

    public function getFP()
    {
        if ( $this->getAttacker()->getSide()->isFP() ) {
            return $this->getAttacker();
        } else {
            return $this->getDefender();
        }
    }

    public function getRollTargetFor(Combatant $c)
    {
        $dL = $this->getDefender()->getLocation();
        if ( $this->getAttacker() == $c ) {
           if ( $dL->isInStronghold() ) {
               return 6;
           } elseif ( $this->getRound() == 1 && $dL->isFirstRoundDefence() ) {
               return 6;
           } else {
               return 5;
           }
        } else {
            return 5;
        }
    }

    public function addCombatRoll($roll, Combatant $c)
    {
        $target = $this->getRollTargetFor($c);
        $c->setCombatRoll($roll);
        $hits = 0;
        // 6 is always a hit regardless of modifier
        foreach( $roll as $r ) {
            if ( $r == 6 || ( $r + $c->getCombatModifier() ) >= $target ) {
                $hits++;
            }
        }
        $c->setCombatRollHits($hits);
        if ( !empty($roll) ) {
            LogFactory::setText("The {$c->getSide()->getName()} rolled " . count($roll) . " dice ("
                . implode(', ', $roll)
                . ") with a modifier of {$c->getCombatModifier()} against a target of {$target} resulting in {$hits} hits.");
        }
    }

    public function addReroll($roll, Combatant $c)
    {
        $target = $this->getRollTargetFor($c);
        $c->setReroll($roll);
        $hits = 0;
        // 6 is always a hit regardless of modifier
        foreach( $roll as $r ) {
            if ( $r == 6 || ( $r + $c->getRerollModifier() ) >= $target ) {
                $hits++;
            }
        }
        $c->setRerollHits($hits);
        if ( !empty($roll) ) {
            LogFactory::setText("The {$c->getSide()->getName()} rerolled " . count($roll) . " dice ("
                . implode(', ', $roll)
                . ") with a modifier of {$c->getRerollModifier()} against a target of {$target} resulting in {$hits} hits.");
        }
    }

    public function takeCasualties($data)
    {
        foreach( $data['units'] as $unit ) {

        }
    }

    public function resolveHits(Side $s, $hits)
    {
        return $this->getOpponent($s)->resolveHits($hits);
    }

    public function advanceRound()
    {
        $this->setRound($this->getRound() + 1);
        $this->getDefender()->cleanup();
        $this->getAttacker()->cleanup();
    }
}
