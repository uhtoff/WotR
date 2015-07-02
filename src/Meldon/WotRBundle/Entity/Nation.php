<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Util\Debug;

/**
 * Description of Nation
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.nation")
 */
class Nation {
//    use MagicDetails;
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
     * @ORM\ManyToOne(targetEntity="NationDetails", fetch="EAGER")
     */
    private $details;
    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="nations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
//    /**
//     * @ORM\ManyToOne(targetEntity="NationCollection", inversedBy="nations", cascade={"persist","remove"})
//     * @ORM\JoinColumn(onDelete="CASCADE")
//     */
//    private $collection;
    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $active;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $stepsFromWar;
    public function __construct(NationDetails $details = NULL, Game $game = NULL)
    {
        if ( $details ) $this->setDetails($details);
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

    public function getName()
    {
        return $this->getDetails()->getName();
    }
    /**
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return Nation
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->addNation($this);
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
     * Set active
     *
     * @param boolean $active
     * @return Nation
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set details
     *
     * @param \Meldon\WotRBundle\Entity\NationDetails $details
     * @return Nation
     */
    public function setDetails(\Meldon\WotRBundle\Entity\NationDetails $details = null)
    {
        $this->details = $details;
        $this->setActive($details->getStartActive());
        $this->setStepsFromWar($details->getStartStep());
        return $this;
    }

    /**
     * Get details
     *
     * @return \Meldon\WotRBundle\Entity\NationDetails 
     */
    public function getDetails()
    {
        return $this->details;
    }

    public function atWar()
    {
        return $this->getStepsFromWar() === 0;
    }
    /**
     * Return true if At War
     * @return boolean
     */
    public function canRecruit()
    {
        if ( $this->AtWar() )
        {
            return true;
        } else
        {
            return false;
        }
    }

    /**
     * Return true if can advance - not At War and if one step from war the active
     * @return bool
     */
    public function canAdvance()
    {
        if ( $this->atWar() ||
                ( $this->getStepsFromWar() === 1 && !$this->getActive() ) )
        {
            return false;
        } else 
        {
            return true;
        }
    }

    public function advanceDisplay()
    {
        $d = $this->getName() . ' - ' . $this->getStepName() . ' (';
        $d .= $this->getActive() ? 'Active' : 'Inactive';
        $d .= ')';
        return $d;
    }

    /**
     * Set stepsFromWar
     *
     * @param integer $stepsFromWar
     * @return Nation
     */
    public function setStepsFromWar($stepsFromWar)
    {
        $this->stepsFromWar = $stepsFromWar;

        return $this;
    }

    /**
     * Get stepsFromWar
     *
     * @return integer 
     */
    public function getStepsFromWar()
    {
        return $this->stepsFromWar;
    }

    /**
     * Convert steps from War into a string for display
     * @return string
     */
    public function getStepName()
    {
        if ( $this->atWar() )
        {
            return "At War";
        } else {
            return $this->getStepsFromWar() . " Steps From War";
        }
    }

    /**
     * Move towards War
     */
    public function advance()
    {
        $this->setStepsFromWar($this->getStepsFromWar()-1);
    }
}
