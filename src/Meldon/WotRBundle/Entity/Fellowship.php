<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Meldon\WotRBundle\Factory\LogFactory;

/**
 * Description of Fellowship
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.fellowship")
 */
class Fellowship {
    const GOLLUM = 11;
    const FRODO = 10;
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
     * @ORM\OneToMany(targetEntity="Character",mappedBy="fellowship", cascade={"persist","remove"})
     */
    private $characters;
    /**
     *
     * @ORM\OneToOne(targetEntity="Location", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $location;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    private $progress = 0;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    private $corruption;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $revealed = false;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $moved = false;
    /**
     *
     * @ORM\OneToOne(targetEntity="Game", inversedBy="fellowship")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;

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
     * Set progess
     *
     * @param integer $progress
     * @return Fellowship
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progess
     *
     * @return integer 
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set corruption
     *
     * @param integer $corruption
     * @return Fellowship
     */
    public function setCorruption($corruption)
    {
        $this->corruption = $corruption;

        return $this;
    }

    /**
     * Get corruption
     *
     * @return integer 
     */
    public function getCorruption()
    {
        return $this->corruption;
    }
    
    public function addCorruption($number = 1)
    {
        $c = $this->getCorruption() + $number;
        $this->setCorruption(min(array($c, 12)));
    }
    
    public function healCorruption($number = 1)
    {
        $c = $this->getCorruption() - $number;
        $this->setCorruption(max(array($c, 0)));
    }

    /**
     * Set revealed
     *
     * @param boolean $revealed
     * @return Fellowship
     */
    public function setRevealed($revealed)
    {
        $this->revealed = $revealed;

        return $this;
    }

    /**
     * Get revealed
     *
     * @return boolean 
     */
    public function getRevealed()
    {
        return $this->revealed;
    }

    /**
     * Set guide
     *
     * @param \Meldon\WotRBundle\Entity\Character $guide
     * @return Fellowship
     */
    public function setGuide(\Meldon\WotRBundle\Entity\Character $guide = null)
    {
        foreach( $this->getCharacters() as $c ) {
            if ( $c == $guide ) {
                $c->setGuide(true);
            } else {
                $c->setGuide(false);
            }
        }
        return $this;
    }

    /**
     * Get guide
     *
     * @return \Meldon\WotRBundle\Entity\Character 
     */
    public function getGuide()
    {
        foreach( $this->getCharacters() as $c ) {
            if ( $c->isGuide() ) {
                return $c;
            }
        }
    }

    /**
     * Set location
     *
     * @param \Meldon\WotRBundle\Entity\Location $location
     * @return Fellowship
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
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return Fellowship
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->setFellowship($this);
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
     * Constructor
     */
    public function __construct()
    {
        $this->characters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add characters
     *
     * @param \Meldon\WotRBundle\Entity\Character $characters
     * @return Fellowship
     */
    public function addCharacter(\Meldon\WotRBundle\Entity\Character $characters)
    {
        $this->characters[] = $characters;

        return $this;
    }

    /**
     * Remove characters
     *
     * @param \Meldon\WotRBundle\Entity\Character $characters
     */
    public function removeCharacter(\Meldon\WotRBundle\Entity\Character $characters)
    {
        $this->characters->removeElement($characters);
    }

    /**
     * Get characters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCharacters()
    {
        return $this->characters;
    }
    public function getNumCharacters()
    {
        return count($this->getCharacters());
    }

    public function getCharactersCanLeave()
    {
        return $this->characters->filter(
            function ($c) {
                /** $var $c Character */
                return $c->getDetailsID() != self::GOLLUM && $c->getDetailsID() != self::FRODO;
            }
        );
    }

    /**
     * @param $dID
     * @return Character
     */
    public function getCharacterByDetailsID($dID)
    {
        return $this->characters->filter(
            function ($e) use ($dID)
            {
                /** @var $e Character */
                return $e->getDetailsID() == $dID;
            }
        )->first();
    }
    public function isInMordor()
    {
        return $this->getLocation()->isInMordor();
    }

    /**
     * Basic declare FS function
     * @param Location $location
     */
    public function declareFS(Location $location = NULL)
    {
        if ( !$location )
        {
            $location = $this->getLocation();
        }
        $this->setLocation($location);
        // Declare in a City or Stronghold that is still under FP control gets you a Corruption healed
        if ( ( $location->isCity() || $location->isStronghold() ) && $location->getSide()->getAbbreviation() === 'FP' )
        {
            $this->healCorruption();
            LogFactory::setText("Healing Corruption due to their care in the settlement, Corruption is now " . $this->getCorruption() . ".");
        }
        $this->setProgress(0);
    }
    public function isHidden()
    {
        return !$this->getRevealed();
    }
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPossibleGuide()
    {
        if ( $this->getGuide() ) {
            $gL = $this->getGuide()->getLevel();
        } else {
            $gL = max($this->getCharacters()->map(function($u){return $u->getLevel();})->toArray());
        }
        $pG = new \Doctrine\Common\Collections\ArrayCollection();
        foreach( $this->getCharacters() as $c )
        {
            if ( $c->getLevel() >= $gL && $c != $this->getGuide() )
            {
                $pG->add($c);
            }
        }
        return $pG;
    }

    /**
     * Set moved
     *
     * @param boolean $moved
     * @return Fellowship
     */
    public function setMoved($moved)
    {
        $this->moved = $moved;

        return $this;
    }

    /**
     * Get moved
     *
     * @return boolean 
     */
    public function getMoved()
    {
        return $this->moved;
    }

    /**
     * Return the Fellowship position as a string
     * @return string
     */
    public function getCurrentPosition()
    {
        $loc = $this->getLocation()->getName();
        $steps = $this->getProgress();
        switch( $steps ) {
            case 0:
                return "in {$loc}";
                break;
            case 1:
                return "{$steps} step from {$loc}";
                break;
            default:
                return "{$steps} steps from {$loc}";
                break;
        }
    }

    public function move()
    {
        $progress = $this->getProgress()+1;
        $this->setProgress($progress);
        $this->setMoved(true);
        LogFactory::setText("The Fellowship move and are {$this->getCurrentPosition()}.");
    }

    public function getRandomCompanion()
    {
        $companions = $this->getCharacters()->filter(
            function ($c) {
                return $c->getLevel() > 0;
            }
        );
        $dice = Dice::roll(1, $companions->count());
        $companion = $companions->get($companions->getKeys()[$dice->results[1] - 1]);
        return $companion;
    }
}
