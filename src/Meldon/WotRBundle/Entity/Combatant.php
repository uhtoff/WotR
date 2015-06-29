<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 07/06/2015
 * Time: 12:38
 */

namespace Meldon\WotRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Battle
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.combatant")
 */

class Combatant {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     * @ORM\OneToMany(targetEntity="Unit", mappedBy="combatant")
     */
    private $units;
    /**
     * @ORM\ManyToOne(targetEntity="Location")
     */
    private $location;
    /**
     * @ORM\ManyToOne(targetEntity="Card")
     */
    private $card;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $preCombatRollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $combatRollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $rerollHits;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $extraHits;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $combatRoll;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $reroll;
    /**
     * @ORM\Column(type="simple_array", nullable = true)
     */
    private $preCombatRoll;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $hitsToResolve;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $combatModifier;
    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private $rerollModifier;
    /**
     * @ORM\Column(type="integer")
     */
    private $strength;
    /**
     * @ORM\Column(type="integer")
     */
    private $leadership;
    /**
     * @ORM\Column(type="integer")
     */
    private $nazgulLeadership;
    /**
     * Constructor
     */
    public function __construct(Side $side)
    {
        $this->setSide($side);
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
     * Set preCombatRollHits
     *
     * @param integer $preCombatRollHits
     * @return Combatant
     */
    public function setPreCombatRollHits($preCombatRollHits)
    {
        $this->preCombatRollHits = $preCombatRollHits;
        $this->setHitsToResolve($this->getHitsToResolve() + $preCombatRollHits);
        return $this;
    }

    /**
     * Get preCombatRollHits
     *
     * @return integer 
     */
    public function getPreCombatRollHits()
    {
        return $this->preCombatRollHits;
    }

    /**
     * Set combatRollHits
     *
     * @param integer $combatRollHits
     * @return Combatant
     */
    public function setCombatRollHits($combatRollHits)
    {
        $this->combatRollHits = $combatRollHits;
        $this->setHitsToResolve($this->getHitsToResolve() + $combatRollHits);
        return $this;
    }

    /**
     * Get combatRollHits
     *
     * @return integer 
     */
    public function getCombatRollHits()
    {
        return $this->combatRollHits;
    }

    /**
     * Set rerollHits
     *
     * @param integer $rerollHits
     * @return Combatant
     */
    public function setRerollHits($rerollHits)
    {
        $this->rerollHits = $rerollHits;
        $this->setHitsToResolve($this->getHitsToResolve() + $rerollHits);
        return $this;
    }

    /**
     * Get rerollHits
     *
     * @return integer 
     */
    public function getRerollHits()
    {
        return $this->rerollHits;
    }

    /**
     * Set extraHits
     *
     * @param integer $extraHits
     * @return Combatant
     */
    public function setExtraHits($extraHits)
    {
        $this->extraHits = $extraHits;
        $this->setHitsToResolve($this->getHitsToResolve() + $extraHits);
        return $this;
    }

    /**
     * Get extraHits
     *
     * @return integer 
     */
    public function getExtraHits()
    {
        return $this->extraHits;
    }

    /**
     * Set combatRoll
     *
     * @param array $combatRoll
     * @return Combatant
     */
    public function setCombatRoll($combatRoll)
    {
        $this->combatRoll = $combatRoll;

        return $this;
    }

    /**
     * Get combatRoll
     *
     * @return array 
     */
    public function getCombatRoll()
    {
        return $this->combatRoll;
    }

    /**
     * Set reroll
     *
     * @param array $reroll
     * @return Combatant
     */
    public function setReroll($reroll)
    {
        $this->reroll = $reroll;

        return $this;
    }

    /**
     * Get reroll
     *
     * @return array 
     */
    public function getReroll()
    {
        return $this->reroll;
    }

    /**
     * Set preCombatRoll
     *
     * @param array $preCombatRoll
     * @return Combatant
     */
    public function setPreCombatRoll($preCombatRoll)
    {
        $this->preCombatRoll = $preCombatRoll;

        return $this;
    }

    /**
     * Get preCombatRoll
     *
     * @return array 
     */
    public function getPreCombatRoll()
    {
        return $this->preCombatRoll;
    }

    /**
     * Set hitsToResolve
     *
     * @param integer $hitsToResolve
     * @return Combatant
     */
    public function setHitsToResolve($hitsToResolve)
    {
        $this->hitsToResolve = $hitsToResolve;

        return $this;
    }

    /**
     * Get hitsToResolve
     *
     * @return integer 
     */
    public function getHitsToResolve()
    {
        return $this->hitsToResolve;
    }

    /**
     * Set combatModifier
     *
     * @param integer $combatModifier
     * @return Combatant
     */
    public function setCombatModifier($combatModifier)
    {
        $this->combatModifier = $combatModifier;

        return $this;
    }

    /**
     * Add sent value to combat modifier
     * @param integer $cM
     * @return $this
     */
    public function addCombatModifier($cM)
    {
        $this->setCombatModifier($this->getCombatModifier()+$cM);
        return $this;
    }

    /**
     * Get combatModifier
     *
     * @return integer 
     */
    public function getCombatModifier()
    {
        return $this->combatModifier;
    }

    /**
     * Set rerollModifier
     *
     * @param integer $rerollModifier
     * @return Combatant
     */
    public function setRerollModifier($rerollModifier)
    {
        $this->rerollModifier = $rerollModifier;

        return $this;
    }

    /**
     * Add sent value to reroll modifier
     * @param integer $rM
     * @return $this
     */
    public function addRerollModifier($rM)
    {
        $this->setRerollModifier($this->getRerollModifier()+$rM);
        return $this;
    }

    /**
     * Get rerollModifier
     *
     * @return integer 
     */
    public function getRerollModifier()
    {
        return $this->rerollModifier;
    }

    /**
     * Set strength
     *
     * @param integer $strength
     * @return Combatant
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength
     *
     * @return integer 
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Set leadership
     *
     * @param integer $leadership
     * @return Combatant
     */
    public function setLeadership($leadership)
    {
        $this->leadership = $leadership;

        return $this;
    }

    /**
     * Get leadership
     *
     * @return integer 
     */
    public function getLeadership()
    {
        return $this->leadership;
    }

    /**
     * Set nazgulLeadership
     *
     * @param integer $nazgulLeadership
     * @return Combatant
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
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return Combatant
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
     * Add units
     *
     * @param \Meldon\WotRBundle\Entity\Unit $units
     * @return Combatant
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
     * Set location
     *
     * @param \Meldon\WotRBundle\Entity\Location $location
     * @return Combatant
     */
    public function setLocation(\Meldon\WotRBundle\Entity\Location $location = null)
    {
        $this->location = $location;

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

    /**
     * Set card
     *
     * @param \Meldon\WotRBundle\Entity\Card $card
     * @return Combatant
     */
    public function setCard(\Meldon\WotRBundle\Entity\Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return \Meldon\WotRBundle\Entity\Card 
     */
    public function getCard()
    {
        return $this->card;
    }

    public function setCombatValues()
    {
        $s = $l = $nL = 0;
        /** @var Unit $u */
        foreach( $this->getUnits() as $u ) {
            $s += $u->getStrength();
            $l += $u->getLeadership();
            if ( $u->isNazgul() ) {
                $nL += $u->getLeadership();
            }
        }
        $this->setStrength($s);
        $this->setLeadership($l);
        $this->setNazgulLeadership($nL);
    }

    public function getHits()
    {
        $hits = 0;
        foreach( $this->getUnits() as $u ) {
            $hits += $u->getHits();
        }
        return $hits;
    }

    public function getNumDice()
    {
        return min($this->getStrength(),5);
    }

    public function getRerollDice()
    {
        return min($this->getNumDice()-$this->getCombatRollHits(),5);
    }

    public function resolveHits($hits)
    {
        $this->setHitsToResolve($this->getHitsToResolve()-$hits);
        return $this->getHitsToResolve();
    }

    public function cleanup()
    {
        $this->setCombatRoll(NULL);
        $this->setReroll(NULL);
        $this->setHitsToResolve(0);
        $this->setCombatModifier(0);
        $this->setRerollModifier(0);
        $this->setRerollHits(0);
        $this->setCombatRollHits(0);
        $this->setPreCombatRoll(NULL);
        $this->setPreCombatRollHits(0);
        $this->setExtraHits(0);
        $this->setCard(NULL);
        $this->setCombatValues();
    }
}
