<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of DecisionStack
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.decisionstack")
 */
class DecisionStack {

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
     * @ORM\OneToOne(targetEntity="Game", inversedBy="decisionStack")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    /**
     * @ORM\OneToMany(targetEntity="Decision", mappedBy="stack",cascade={"persist"},orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $decisions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->decisions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return DecisionStack
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->setDecisionStack($this);
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
     * Add decisions
     *
     * @param \Meldon\WotRBundle\Entity\Decision $decisions
     * @return DecisionStack
     */
    public function addDecision(\Meldon\WotRBundle\Entity\Decision $decisions)
    {
        $this->decisions[] = $decisions;

        return $this;
    }

    /**
     * Remove decisions
     *
     * @param \Meldon\WotRBundle\Entity\Decision $decisions
     */
    public function removeDecision(\Meldon\WotRBundle\Entity\Decision $decisions)
    {
        $this->decisions->removeElement($decisions);
    }

    /**
     * Get decisions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDecisions()
    {
        return $this->decisions;
    }
    /**
     * Return number of decisions on the stack
     * @return integer
     */
    public function numOnStack()
    {
        return count( $this->getDecisions() );
    }
    
    /**
     * Map through array collection getting positions of all decisions
     * @return integer[]
     */
    public function getPositions() 
    {
        return $this->getDecisions()
                ->map(function($d){return $d->getPosition();})->toArray();
    }
    /**
     * 
     * @return Decision
     */
    public function getCurrentDecision()
    {
        return $this->getDecisions()->first();
    }
    /**
     * Returns the top decision from the array
     * @return Decision|boolean
     */
    public function takeFromTop() 
    {
        $d = $this->getDecisions()->first();
        if ( $d !== false )
        {
            $this->removeDecision($d);
            return $d;
        } else 
        {
            return false;
        }
    }
    /**
     * Returns the bottom decision from the array
     * @return Decision|boolean
     */
    public function takeFromBottom() 
    {
        $d = $this->getDecisions()->last();
        if ( $d !== false )
        {
            $this->removeDecision($d);
            return $d;
        } else {
            return false;
        }
    }
    /**
     * Adds a decision to the top of the stack, shuffles down the positions if neccessary
     * @param \Meldon\WotRBundle\Entity\Decision $d
     * @return \Meldon\WotRBundle\Entity\Decision
     */
    public function addToTop(Decision $d)
    {
        $pos = $this->getPositions();
        // If card group is empty
        if ( count($pos) != 0 ) {
            $topPos = min($this->getPositions());        
            if ( $topPos == 1 ) {
                $this->getDecisions()
                        ->map(function($d){$d->setPosition($d->getPosition() + 1);});
            }
        }        
        $d->setPosition(1);
        $d->setStack($this);
        return $d;
    }
    /**
     * Adds a decision to the bottom of the stack
     * @param \Meldon\WotRBundle\Entity\Decision $card
     * @return \Meldon\WotRBundle\Entity\Decision
     */
    public function addToBottom(Decision $d) {
        $pos = $this->getPositions();
        if ( count($pos) == 0 )
        {
            $bottomPos = 0;
        } else 
        {
            $bottomPos = max($pos);
        }
        $d->setPosition($bottomPos + 1);
        $d->setStack($this);
        return $d;
    }
}
