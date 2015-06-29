<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Meldon\WotRBundle\Collection\GameCollection;
use Meldon\WotRBundle\Collection\NationCollection;
use Meldon\WotRBundle\Collection\WotRCollection;
use Meldon\WotRBundle\Factory\LogFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Meldon\WotRBundle\Entity\HuntTile;
use Meldon\WotRBundle\Entity\Card;
use Meldon\WotRBundle\Entity\CardGroup;
use Meldon\WotRBundle\Entity\Side;
use Meldon\WotRBundle\Entity\Player;
use Meldon\WotRBundle\Entity\Action;
use Meldon\WotRBundle\Entity\Nation;
use Meldon\WotRBundle\Entity\Log;
use Doctrine\Common\Util\Debug;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Meldon\WotRBundle\Event\LogItem;

/**
 * Description of Game
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="GameRepository")
 * @ORM\Table(name="wotrnew.game")
 */
class Game {
    const MAX_HAND_SIZE = 6;
    const STRIDER = 6;
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
     * @ORM\ManyToOne(targetEntity="Scenario")
     */
    private $scenario;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    private $turn = 1;
    /**
     * @var Phase
     * @ORM\ManyToOne(targetEntity="Phase")
     */
    private $phase;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $subphase = 1;
    /**
     * @ORM\OneToOne(targetEntity="Fellowship", mappedBy="game", cascade={"persist","remove"})
     */
    private $fellowship;
    /**
     * @ORM\OneToMany(targetEntity="ElvenRings", mappedBy="game", cascade={"persist","remove"})
     */
    private $rings;
    /**
     * @ORM\OneToMany(targetEntity="Nation", mappedBy="game", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $nations;
    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="game", cascade={"persist","remove"})
     */
    private $locations;
    /**
     * @ORM\OneToMany(targetEntity="CardGroup", mappedBy="game", cascade={"persist","remove"})
     */
    private $cardGroups;
    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="game", cascade={"persist","remove"})
     */
    private $players;
    /**
     * @ORM\OneToMany(targetEntity="Unit", mappedBy="game", indexBy="id", cascade={"persist","remove"})
     */
    private $units;
    /**
     * @ORM\Column(type="integer")
     */
    private $huntDice = 0;
    /**
     * @ORM\OneToMany(targetEntity="ActionDie", mappedBy="game", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $actionDice;
    /**
     * @ORM\OneToOne(targetEntity="Log", mappedBy="game", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $log;
    /**
     * @ORM\OneToOne(targetEntity="ActionStack", mappedBy="game", cascade={"persist"})
     */
    private $actionStack;
    /**
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $currSide;
    /**
     * @ORM\OneToOne(targetEntity="Battle", inversedBy="game", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $battle;


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
     * Set fellowship
     *
     * @param \Meldon\WotRBundle\Entity\Fellowship $fellowship
     * @return Game
     */
    public function setFellowship(\Meldon\WotRBundle\Entity\Fellowship $fellowship = null)
    {
        $this->fellowship = $fellowship;
        return $this;
    }

    /**
     * Get fellowship
     *
     * @return \Meldon\WotRBundle\Entity\Fellowship 
     */
    public function getFellowship()
    {
        return $this->fellowship;
    }

    /**
     * Set turn
     *
     * @param integer $turn
     * @return Game
     */
    public function setTurn($turn)
    {
        $this->turn = $turn;

        return $this;
    }

    /**
     * Get turn
     *
     * @return integer 
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * Set phase
     *
     * @param Phase $phase
     * @return Game
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;
        $nA = new Action($phase->getActionDetails());
        $this->setCurrentAction($nA);
        return $this;
    }

    /**
     * Get phase
     *
     * @return Phase
     */
    public function getPhase()
    {
        return $this->phase;
    }
    /**
     * Set scenario
     *
     * @param \Meldon\WotRBundle\Entity\Scenario $scenario
     * @return Game
     */
    public function setScenario(\Meldon\WotRBundle\Entity\Scenario $scenario = null)
    {
        $this->scenario = $scenario;
        return $this;
    }

    /**
     * Get scenario
     *
     * @return \Meldon\WotRBundle\Entity\Scenario 
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * Get characters
     *
     * @return \Doctrine\Common\Collections\Collection|Character[]
     */
    public function getCharacters()
    {
        return $this->getUnits()->filter(
            function ($c) {
                return $c->getType()->getName() == 'Character';
            }
        );
    }

    /**
     * Add nations
     *
     * @param Nation $nations
     * @return Game
     */
    public function addNation(Nation $nations)
    {
        $this->nations[] = $nations;

        return $this;
    }

    /**
     * Remove nations
     *
     * @param Nation $nations
     */
    public function removeNation(Nation $nations)
    {
        $this->nations->removeElement($nations);
    }

    /**
     * Get nations - if side passed, just get that side
     * @param Side $side
     * @return NationCollection
     */
    public function getNations(Side $side = NULL)
    {
        if ( $side )
        {
            return $this->nations->filter(
                function($e) use ($side)
                /** @var $e Nation */
                {
                    return $e->getDetails()->getSide() === $side;
                }
            );
        } else
        {
            return new NationCollection($this->nations);
        }
    }
    /**
     * Pre-req method to check if a side can recruit if side not sent 
     * use current side
     * @TODO Doesn't take into account unit availability
     * @param Side $side
     * @return Nation[]
     */
    public function canRecruit(Side $side = NULL)
    {
        $canRecruit = array();
        if (is_null($side))
        {
            $side = $this->getCurrentSide();
        }
        /** @var $nation Nation */
        foreach( $this->getNations($side) as $nation )
        {
            if ( $nation->canRecruit() )
            {
                $canRecruit[] = $nation->getDetails();
            }
        }
        return $canRecruit;            
    }
    public function canAdvance(Side $side = NULL)
    {
        $canAdvance = array();
        if (is_null($side))
        {
            $side = $this->getCurrentSide();
        }
        /** @var $nation Nation */
        foreach( $this->getNations($side) as $nation )
        {
            if ( $nation->canAdvance() )
            {
                $canAdvance[] = $nation;
            }
        }
        return $canAdvance;            
    }
    /**
     * Add locations
     *
     * @param \Meldon\WotRBundle\Entity\Location $locations
     * @return Game
     */
    public function addLocation(\Meldon\WotRBundle\Entity\Location $locations)
    {
        $this->locations[] = $locations;

        return $this;
    }

    /**
     * Remove locations
     *
     * @param \Meldon\WotRBundle\Entity\Location $locations
     */
    public function removeLocation(\Meldon\WotRBundle\Entity\Location $locations)
    {
        $this->locations->removeElement($locations);
    }

    /**
     * Get locations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLocations()
    {
        return $this->locations;
    }
  

    /**
     * Add cardGroups
     *
     * @param \Meldon\WotRBundle\Entity\CardGroup $cardGroups
     * @return Game
     */
    public function addCardGroup(\Meldon\WotRBundle\Entity\CardGroup $cardGroups)
    {
        $this->cardGroups[] = $cardGroups;

        return $this;
    }

    /**
     * Remove cardGroups
     *
     * @param \Meldon\WotRBundle\Entity\CardGroup $cardGroups
     */
    public function removeCardGroup(\Meldon\WotRBundle\Entity\CardGroup $cardGroups)
    {
        $this->cardGroups->removeElement($cardGroups);
    }

    /**
     * Get cardGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCardGroups()
    {
        return $this->cardGroups;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->nations = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->cardGroups = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->actionDice = new ArrayCollection();
    }
    /**
     * 
     * @param integer $id
     * @return CardGroup
     */
    public function getCardGroupByID($id)
    {
        $cardGroup = $this->getCardGroups()->filter(
                function($entry) use ($id)
                {
                    if ( $entry->getDetailsID() == $id )
                    {
                        return true;
                    }
                    return false;
                }
            );
        return $cardGroup->first();
    }
    /**
     * @return CardGroup
     */
    public function getHuntPool() {
        return $this->getCardGroupByID(1);
    }
    /**
     * @return CardGroup
     */
    public function getHuntDiscard() {
        return $this->getCardGroupByID(2);
    }
    /**
     * @return CardGroup
     */
    public function getHuntRemoved()
    {
        return $this->getCardGroupByID(3);
    }
    /**
     * @return CardGroup
     */
    public function getHuntWaiting()
    {
        return $this->getCardGroupByID(4);
    }
    /**
     * @return CardGroup
     */
    public function getFPHand() {
        return $this->getCardGroupByID(5);
    }
    /**
     * @return CardGroup
     */
    public function getSHand() {
        return $this->getCardGroupByID(6);
    }
    /**
     * @return CardGroup
     */
    public function getFPCharacterDeck()
    {
        return $this->getCardGroupByID(7);
    }
    /**
     * @return CardGroup
     */
    public function getFPStrategyDeck()
    {
        return $this->getCardGroupByID(8);
    }
        /**
     * @return CardGroup
     */
    public function getSCharacterDeck()
    {
        return $this->getCardGroupByID(9);
    }
    /**
     * @return CardGroup
     */
    public function getSStrategyDeck()
    {
        return $this->getCardGroupByID(10);
    }
    /**
     * @return CardGroup
     */
    public function getCardsOnTableGroup()
    {
        return $this->getCardGroupByID(13);
    }
    public function getHuntTileToResolveGroup()
    {
        return $this->getCardGroupByID(15);
    }
    /**
     * 
     * @param Side $side
     * @return CardGroup
     */
    public function getHand(Side $side)
    {
        $method = "get{$side->getAbbreviation()}Hand";
        return $this->$method();
    }
    /**
     * 
     * @param Side $side
     * @param string $type
     * @return CardGroup
     */
    public function getDeck(Side $side, $type)
    {
        $method = "get{$side->getAbbreviation()}{$type}Deck";
        return $this->$method();
    }
    public function drawCardBySide(Side $side, $type)
    {
        $hand = $this->getHand($side);
        $this->drawCard($hand, $type);
    }
    public function drawCard(CardGroup $hand, $type)
    {
        $deck = $this->getCardGroupByID($hand->getDrawRoute($type)->getDestination()->getId());
        if ( $deck->takeFromTop() )
        {
            $hand->addToBottom($deck->takeFromTop());
        }
    }

    /**
     * 
     * @param HuntTile $huntTile
     * @return HuntTile
     */
    public function returnHuntTile(Card $huntTile)
    {
        $huntPool = $this->getHuntPool();
        $huntPool->addToTop($huntTile);
        return $huntTile;
    }
    /**
     * 
     * @param Card $huntTile
     * @return Card
     */
    public function removeHuntTile(Card $huntTile)
    {
        $huntRemoved = $this->getHuntRemoved();
        $huntRemoved->addToTop($huntTile);
        return $huntTile;
    }
    /**
     * Add special Hunt Tile ready to be used
     * @param integer $dID
     * @return Card
     */
    public function addSpecialHuntTile($dID)
    {
        if ( $this->getFellowship()->isInMordor() )
        {
            $specialDestination = $this->getHuntPool();
        } else
        {
            $specialDestination = $this->getHuntWaiting();
        }
        $huntTile = $this->getHuntRemoved()->getCardByDetailsID($dID);
        $specialDestination->addToTop($huntTile);
        $specialDestination->shuffle();
        return $huntTile;
    }
    /**
     * Deal with all the changes needed to move the Fellowship into Mordor
     * @TODO Move fellowship to first step
     */
    public function enterMordor()
    {
        $huntDiscard = $this->getHuntDiscard();
        $huntPool = $this->getHuntPool();
        /* @var $huntTile HuntTile */
        while( $huntTile = $huntDiscard->takeFromTop() )
        {
            if ( $huntTile->getCorruption() == 'e' )
            {
                $this->returnHuntTile($huntTile);
            } else 
            {
                $this->removeHuntTile($huntTile);
            }
        }
        $this->getHuntWaiting()->moveAllTo($huntPool);
        $huntPool->shuffle();
    }

    public function processHuntTile(Card $tile, $hits = 0)
    {
        
    }
    /**
     * Get damage from hunt tile converting 'e' results into an integer
     * @param \Meldon\WotRBundle\Entity\HuntTile $tile
     * @param integer $hits
     * @return integer
     */
    public function getHuntDamage(Card $tile, $hits = NULL)
    {
        $tileDamage = $tile->getCorruption();
        if ( $tileDamage == 'e' ) {
            
        } 
        return $tileDamage;
    }
    /**
     * @param integer $damage
     */
    public function addHuntDamage($damage)
    {
        $corruption = $this->getFellowship()
                        ->getCorruption();
        $newCorruption = $corruption + $damage;
        $this->getFellowship()
                ->setCorruption($newCorruption);
        return $newCorruption;
    }
    public function revealFellowship()
    {
        $this->getFellowship()->setRevealed(true);
    }
    public function hideFellowship()
    {
        $this->getFellowship()->setRevealed(false);
        LogFactory::setText('The Fellowship has passed beyond the sight of the Great Eye.');
    }

    /**
     * Add players
     *
     * @param \Meldon\WotRBundle\Entity\Player $players
     * @return Game
     */
    public function addPlayer(\Meldon\WotRBundle\Entity\Player $players)
    {
        $this->players[] = $players;

        return $this;
    }

    /**
     * Remove players
     *
     * @param \Meldon\WotRBundle\Entity\Player $players
     */
    public function removePlayer(\Meldon\WotRBundle\Entity\Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }
    /**
     * 
     * @return Side[]
     */
    public function getSides()
    {
        $sides = array();
        foreach( $this->getPlayers() as $player )
        {
            $sides[] = $player->getSide();
        }
        return $sides;
    }

    /**
     * Set subphase
     *
     * @param integer $subphase
     * @return Game
     */
    public function setSubphase($subphase)
    {
        $this->subphase = $subphase;

        return $this;
    }

    /**
     * Get subphase
     *
     * @return integer 
     */
    public function getSubphase()
    {
        return $this->subphase;
    }

    /**
     * Add rings
     *
     * @param \Meldon\WotRBundle\Entity\ElvenRings $rings
     * @return Game
     */
    public function addRing(\Meldon\WotRBundle\Entity\ElvenRings $rings)
    {
        $this->rings[] = $rings;

        return $this;
    }

    /**
     * Remove rings
     *
     * @param \Meldon\WotRBundle\Entity\ElvenRings $rings
     */
    public function removeRing(\Meldon\WotRBundle\Entity\ElvenRings $rings)
    {
        $this->rings->removeElement($rings);
    }

    /**
     * Get rings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRings()
    {
        return $this->rings;
    }

    /**
     * Add units
     *
     * @param \Meldon\WotRBundle\Entity\Unit $units
     * @return Game
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
     * Get units - default all units, pass true to get only those offBoard
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUnits($offBoard = false)
    {
        if ( $offBoard )
        {
            return $this->units->filter(
                    function($entry)
                    {
                        return is_null($entry->getLocation());
                    });
        } else
        {
            return new WotRCollection($this->units);
        }
    }

    public function advancePhase()
    {
        $this->setPhase($this->getPhase()->getNextPhase());
    }
    public function advanceTurn()
    {
        $this->setTurn($this->getTurn() + 1);
        $this->setSubphase(1);
    }
//    /**
//     * Set decisionStack
//     *
//     * @param \Meldon\WotRBundle\Entity\DecisionStack $decisionStack
//     * @return Game
//     */
//    public function setDecisionStack(\Meldon\WotRBundle\Entity\DecisionStack $decisionStack = null)
//    {
//        $this->decisionStack = $decisionStack;
//
//        return $this;
//    }
//
//    /**
//     * Get decisionStack
//     *
//     * @return \Meldon\WotRBundle\Entity\DecisionStack
//     */
//    public function getDecisionStack()
//    {
//        return $this->decisionStack;
//    }
    public function getSide($abb)
    {
        $players = $this->getPlayers();
        foreach( $players as $player )
        {
            if ( $player->getSide()->getAbbreviation() == $abb )
            {
                return $player->getSide();
            }
        }
    }
    /**
     *
     * @param Side $side
     * @return Player
     */
    public function getPlayerBySide(Side $side)
    {
        $players = $this->getPlayers();
        return $players->filter(
                function($p) use ($side)
                {
                    return $p->getSide() == $side;
                })->first();
    }
    public function setCurrentSide(Side $side)
    {
        $this->setCurrentPlayer($this->getPlayerBySide($side));
    }
    public function setCurrentPlayer(Player $player)
    {
        $this->currPlayer = $player;
        $this->currSide = $player->getSide();
    }
    /**
     *
     * @return Player
     */
    public function getCurrentPlayer()
    {
        return $this->currPlayer;
    }
    /**
     *
     * @return Player
     */
    public function getOpponent()
    {
        $players = $this->getPlayers();
        $cP = $this->getCurrentPlayer();
        return $players->filter(
                function($p) use ($cP)
                {
                    return $p != $cP;
                })->first();
    }
    /**
     *
     * @return Side
     */
    public function getCurrentSide()
    {
        return $this->currSide;
    }
    /**
     *
     * @return Side
     */
    public function getOpponentSide()
    {
        return $this->getOpponent()->getSide();
    }
    /**
     * Returns decision from the top of the decision stack
     * @return Decision
     */
    public function getDecision()
    {
        return $this->getCurrentAction() && $this->getCurrentAction()->hasDecisionSet($this);
    }

    /**
     * Draws cards from each of the players' decks
     * @TODO Check if there are cards available in the deck
     */
    public function drawPhase()
    {
        $cardType = array('character','strategy');
        foreach( $this->players as $player )
        {
            foreach( $cardType as $type )
            {
                $this->drawCardBySide($player->getSide(), $type);
            }
        }
        LogFactory::setText('2 cards drawn by each side.');
    }

    /**
     * Discards the card provided - default to the top of the discard pile
     * @param Card $card
     * @param string $place
     */
    public function discardCard(Card $card, $place = 'top')
    {
        $cG = $card->getGroup();
        $dG = $this->getCardGroupByID($cG->getDiscardRoute()->getDestination()->getId());
        if ( $place == 'top' )
        {
            $dG->addToTop($card);
        } else
        {
            $dG->addToBottom($card);
        }
    }

    /**
     * Takes an array where the 'card' key holds an array of Card objects and passes them to the discardCard method
     * @param array $data
     */
    public function discardCards($data)
    {
        $text = '';
        foreach( $data['card'] as $card )
        {
            $this->discardCard($card);
            $text .= $card->getName() . '<br/>';
        }
        $text .= 'discarded';
        if ( $this->getHand($this->getCurrentSide())->getCards()->count() > self::MAX_HAND_SIZE ) {
            $this->getCurrentAction()->setCurrentSubActionIndex(0);
        }
        LogFactory::setText($text);
    }

    /**
     * Takes an Action object and a Side object and sets the next decision point in the game
     * If no Side object sent, defaults to the current player
     * @param Action $a
     * @param Side $side
     */
    public function setDecision(Side $side = NULL)
    {
        if ( !$this->getCurrentAction()->getSide() ) {
            if (!$side) {
                if ( $this->getCurrentAction()->getDefaultSide() ) {
                    $sideAbb = $this->getCurrentAction()->getDefaultSide();
                    switch ( $sideAbb ) {
                        case 'FP':
                        case 'S':
                            $side = $this->getSide($sideAbb);
                            break;
                        case 'ATT':
                            $side = $this->getBattle()->getAttacker()->getSide();
                            break;
                        case 'DEF':
                            $side = $this->getBattle()->getDefender()->getSide();
                            break;
                    }
                } else {
                    $side = $this->getCurrentSide();
                }
            }
            $this->getCurrentAction()->setSide($side);
        }
        if ( $this->getCurrentAction()->getSide() !== $this->getCurrentSide() ) {
            LogFactory::setText($side->getName() . ' player to make a decision.');
        }
    }

    /**
     * @TODO Activate Nation if declared in a city or stronghold of that nation
     * Takes a data array from a form, where the 'declare' key is a Location object and sets the Fellowship location to that
     * @param array $data
     */
    public function declareFellowship($data)
    {
        if ( $data['declare'] ) {
            LogFactory::setText('The Fellowship have declared their position in ' . $data['location']->getName() . '.');
            $this->getFellowship()->declareFS($data['location']);
        } else {
            LogFactory::setText("The Fellowship have not declared their position, their last known position remains {$this->getFellowship()->getCurrentPosition()}.");
        }
    }

    /**
     * Log text for the Fellowship being revealed during the Fellowship phase
     */
    public function revealedAtFellowshipPhase()
    {
        LogFactory::setText("The Fellowship remain revealed at {$this->getFellowship()->getLocation()->getName()}.");
    }
    /**
     * Takes a data array with the 'changeGuide' key being a Character object who will become the new Guide
     * @param array $data
     */
    public function chooseGuide($data)
    {
        if ( ( isset($data['changeGuide']) && $data['changeGuide'] ) || !isset($data['changeGuide']) )
        {
            $this->getFellowship()->setGuide($data['guide']);
            LogFactory::setText("The Fellowship have selected {$this->getFellowship()->getGuide()->getName()} to lead them.");
        } else {
            LogFactory::setText("The Fellowship remain guided by {$this->getFellowship()->getGuide()->getName()}.");
        }
    }

    /**
     * Log text for when no new guides are available
     */
    public function noGuideAvailable()
    {
        LogFactory::setText("No-one is qualified to replace {$this->getFellowship()->getGuide()->getName()} as the guide of the Fellowship.");
    }
    /**
     * Takes a data array with the 'huntDice' key being the number of Hunt Dice the Shadow are assigning
     * @param array $data
     */
    public function allocateHuntDice($data)
    {
        $this->getFellowship()->setMoved(false);
        $this->setHuntDice($data['huntDice']);
        switch( $this->getHuntDice() ) {
            case 0:
                $text = "The Shadow allocates no resources to the Hunt for the One Ring.";
                break;
            case 1:
                $text = "The Shadow has allocated a single die to the Hunt for the One Ring.";
                break;
            default:
                $text = "The Shadow has allocated {$this->getHuntDice()} dice to the Hunt for the One Ring.";
                break;
        }
        LogFactory::setText($text);
    }
    /**
     * Returns number of dice needed to roll when rolling action dice
     * @param Side $side
     * @return integer
     */
    public function getNumActionDiceToRoll(Side $side)
    {
        $baseDice = $this->getScenario()->getStartDice($side);
        $characters = $this->filterBySide($this->getCharacters(),$side);
        $charactersWithDice = $characters->filter(
            function ($c) {
                /** @var $c Character */
                return $c->getDetails()->getDice();
            }
        );
        $baseDice += count($charactersWithDice);
        if ( $side->getAbbreviation() === 'S' )
        {
            $baseDice = $baseDice - $this->getHuntDice();
        }
        return $baseDice;
    }

    /**
     * Set huntDice
     *
     * @param integer $huntDice
     * @return Game
     */
    public function setHuntDice($huntDice)
    {
        $this->huntDice = $huntDice;

        return $this;
    }

    /**
     * Get huntDice
     *
     * @return integer
     */
    public function getHuntDice()
    {
        return $this->huntDice;
    }

    /**
     * Add actionDice
     *
     * @param \Meldon\WotRBundle\Entity\ActionDie $actionDice
     * @return Game
     */
    public function addActionDice(\Meldon\WotRBundle\Entity\ActionDie $actionDice)
    {
        $this->actionDice[] = $actionDice;

        return $this;
    }

    /**
     * Remove actionDice
     *
     * @param \Meldon\WotRBundle\Entity\ActionDie $actionDice
     */
    public function removeActionDice(\Meldon\WotRBundle\Entity\ActionDie $actionDice)
    {
        $this->actionDice->removeElement($actionDice);
    }

    /**
     * Get actionDice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionDice()
    {
        return $this->actionDice;
    }
    /**
     * Either a Side abbreviation
     * @param string $type
     * @return ArrayCollection
     */
    public function getDicePoolByType( $type )
    {
        return $this->{'get'.$type.'DicePool'}();
    }

    /**
     * @return mixed
     */
    public function getSDicePool()
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        return $this->getActionDice()->matching(
                $criteria
                ->where($expr->andX(
                        $expr->eq('side',$this->getSide('S')),
                        $expr->neq('number',6),
                        $expr->eq('used',false)))
                ->orderBy(array('number' => Criteria::ASC)));
    }

    /**
     * @return mixed
     */
    public function getFPDicePool()
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        return $this->getActionDice()->matching(
                $criteria
                ->where($expr->andX(
                        $expr->eq('side',$this->getSide('FP')),
                        $expr->eq('used',false)))
                ->orderBy(array('number' => Criteria::ASC)));
    }
    public function getHuntDicePool()
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $usedFSDice = $this->getActionDice()->matching(
                    $criteria->where(
                        $expr->andX(
                            $expr->eq('side',$this->getSide('FP')),
                            $expr->eq('usedForFS',true)
                        )

                    )
                    ->orderBy(array('number' => Criteria::ASC)));
        $eyeDice = $this->getActionDice()->matching(
                $criteria->where(
                    $expr->andX(
                        $expr->eq('side',$this->getSide('S')),
                        $expr->eq('used',false),
                        $expr->eq('number',6)
                    )
                ));
        return new ArrayCollection(
                    array_merge( $eyeDice->toArray(), $usedFSDice->toArray()));
    }
    /**
     *
     * @return ArrayCollection
     */
    public function getPlayerDicePool()
    {
        return $this->getDicePoolByType($this->getCurrentSide()->getAbbreviation());

    }
    /**
     *
     * @return ArrayCollection
     */
    public function getOpponentDicePool()
    {
        return $this->getDicePoolByType($this->getOpponentSide()->getAbbreviation());
    }
    /**
     *
     * @return boolean
     */
    public function canPass()
    {
        if ( count($this->getPlayerDicePool()) >= count($this->getOpponentDicePool()) )
        {
            return false;
        } else
        {
            return true;
        }
    }
    /**
     *
     * @return boolean
     */
    public function areDiceRemaining()
    {
        if ( count($this->getPlayerDicePool()) + count($this->getOpponentDicePool()) > 0 )
        {
            return true;
        } else
        {
            return false;
        }
    }
    public function chooseActionDie($data)
    {
        if ( isset($data['pass']) && $data['pass'] && $this->canPass() )
        {
            // Gets reference to original current action before passing off to passControl so it can be removed
            $a = $this->getCurrentAction();
            LogFactory::setText($a->getSide()->getName() . ' has passed.');
            $this->passControl();
            $this->getActionStack()->removeAction($a);
        } else {
            $this->getCurrentAction()->setActionDie($data['actionDie']);
        }
    }

    /**
     * Passes control based on dice pool and the current subphase
     */
    public function passControl()
    {
        if ( !($this->getFPDicePool()->isEmpty() && $this->getSDicePool()->isEmpty()) ) {
            $nA = new Action($this->getCurrentAction()->getNextAction());
            $this->setCurrentAction($nA);
            $this->setSubphase(($this->getSubphase() + 1));
            var_dump($this->getSubphase() % 2 );

            if ( $this->getFPDicePool()->isEmpty() ) {
                $this->setDecision($this->getSide('S'));
            } elseif ( $this->getSDicePool()->isEmpty() ) {
                $this->getDecision($this->getSide('FP'));
            } elseif ($this->getSubphase() % 2 == 0) {
                $this->setDecision($this->getSide('S'));
            } else {
                $this->setDecision($this->getSide('FP'));
            }
        }
    }

    public function useDie() {
        $this->getCurrentAction()->getActionDie()->markUsed();
        LogFactory::setText("{$this->getCurrentAction()->getActionDie()->getName()} die used.");
    }


    /**
     * Set new Action based on what has been chosen
     * @param $data array
     */
    public function chooseAction($data)
    {
        /** @var $action ActionDetails */
        $action = $data['action'];
        $nA = new Action($action);
        $nA->setActionDie($this->getCurrentAction()->getActionDie());
        $this->setCurrentAction($nA);
    }

    /**
     * Process the form decision, reset back one index if return is sent or back to the dice decision if differentDie is sent
     * Otherwise execute the next SubAction with the data sent
     * @param $data
     */
    public function processDecision($data)
    {
        if ( isset( $data['differentDie'] ) && $data['differentDie'] == true ) {
            $this->removeCurrentAction();
            $this->getCurrentAction()->setCurrentSubActionIndex(1);
        } elseif ( isset( $data['return'] ) && $data['return'] == true ) {
            $this->getCurrentAction()->decreaseIndex();
        } elseif ( isset( $data['complete'] ) && $data['complete'] == true ) {
            $this->removeCurrentAction();
        }  elseif ( isset( $data['sendMessage'] ) && $data['sendMessage'] ) {
            $nA = new Action($this->getCurrentAction()->getData()['message']);
            $this->setCurrentAction($nA);
        } else {
            $this->getCurrentAction()->execute($this, $data);
        }
    }

    /**
     * @return SubAction
     */
    public function getCurrentSubAction()
    {
        if ( $this->getCurrentAction() )
            return $this->getCurrentAction()->getSubAction();
        else
            return false;
    }
    /**
     *
     * @param array $action
     */
    public function drawCardByDeck($action)
    {
        $hand = $this->getHand($this->getCurrentSide());
        /** @var CardGroup $deck */
        $deck = $action['deck'];
        $hand->addToTop($deck->takeFromTop());
        LogFactory::setText("The {$this->getCurrentSide()->getName()} have drawn a card from the {$deck->getName()} deck.");
    }
    public function cleanupDice()
    {
        /** @var $aD ActionDie */
        foreach( $this->getActionDice() as $aD  )
        {
            $aD->removeGame($this);
        }
        $this->setHuntDice(0);
        /** @var $u Unit */
        foreach( $this->getUnits() as $u ) {
            $u->setLastMoved(NULL);
        }
    }
    /**
     * Pre-requisite method for play event actions
     * @return integer
     */
    public function numPlayableCards($type = NULL)
    {
        /** @var $hand CardGroup */
        $hand = $this->getHand($this->getCurrentSide());
        return $hand->numCards($type,true);
    }
    public function advanceNation($data)
    {
        /** @var $nation Nation */
        $nation = $data['nation'];
        $nation->advance();
        LogFactory::setText("The {$nation->getName()} nation have advanced to {$nation->getStepName()}.");
    }
    public function playCardEvent($data)
    {
        /** @var $card EventCard */
        $card = $data['card'];
        $card->playEvent($this, $card);
        if ( !$this->getCardsOnTableGroup()->getCards()->contains($card) )
        {
            $this->discardCard($card);
        }
    }
    /**
     * @param Card $card
     */
    public function playCardToTable(Card $card)
    {
        $table = $this->getCardsOnTableGroup();
        $table->addToBottom($card);
    }
    public function playAthelas()
    {
        $rolls = Dice::roll(3);
        $success = 0;
        if ( $this->getFellowship()->getGuide()->getDetailsID() === Game::STRIDER )
        {
            $target = 3;
        } else
        {
            $target = 5;
        }
        foreach( $rolls->results as $result )
        {
            if ( $result >= $target )
            {
                $success++;
            }
        }
        $this->getFellowship()->healCorruption($success);

    }

    /**
     * Set log
     *
     * @param \Meldon\WotRBundle\Entity\Log $log
     * @return Game
     */
    public function setLog(\Meldon\WotRBundle\Entity\Log $log = null)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return \Meldon\WotRBundle\Entity\Log
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set currentAction
     *
     * @param Action $currentAction
     * @return Game
     */
    public function setCurrentAction(Action $currentAction = null)
    {
        $this->getActionStack()->addToTop($currentAction);
        return $this;
    }
    public function removeCurrentAction()
    {
        $this->getActionStack()->takeFromTop();
    }
    /**
     * Get currentAction
     *
     * @return Action
     */
    public function getCurrentAction()
    {
        return $this->getActionStack()->getCurrentAction();
    }

    /**
     * Set currSide
     *
     * @param Side $currSide
     * @return Game
     */
    public function setCurrSide(Side $currSide = null)
    {
        $this->currSide = $currSide;

        return $this;
    }

    /**
     * Get currSide
     *
     * @return Side
     */
    public function getCurrSide()
    {
        return $this->currSide;
    }

    /**
     * Return a collection of locations that contain units - if $side is set, only return those with units from that side
     * If unmoved is true then it only returns locations where there are units that are unmoved this subphase
     * @param Side $side
     * @param bool $unmoved
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocationsWithUnits(Side $side = NULL, $unmoved = false, $leader = false) {
        $locs = $this->getLocations()->filter(
            function ($e) {
                return $e->getUnits()->count() > 0;
            }
        );
        if ( $side ) {
            $locs = $locs->filter(
                function ($e) use ($side) {
                    return $e->getUnits()->first()->getSide() === $side;
                }
            );
        }
        $subPhase = $this->getSubphase();
        if ( $unmoved ) {
            $locs = $locs->filter(
                function ($e) use ($subPhase) {
                    return !$e->getUnits()->forAll(
                        function ($k,$u) use ($subPhase) {
                            return $u->getLastMoved() == $subPhase;
                        }
                    );
                }
            );
        }
        if ( $leader ) {
            $self = $this;
            $locs = $locs->filter(
                function ($l) use ($self) {
                    return !$l->getUnits()->filter(
                        function ($u) use ($self) {
                            return $u->isLeader($self);
                        }
                    )->isEmpty();
                }
            );
        }
        return $locs;
    }
    public function getAdjacentLocationsWithEnemyUnits(Location $loc, Side $side) {
        foreach( $this->getSides() as $s ) {
            if ( $s != $side ) {
                $oppSide = $s;
            }
        }
        $locs = $loc->getAttackableLocs();
        return $locs->filter(
            function($l) use($oppSide) {
                return $l->hasUnitsFromSide($oppSide);
            }
        );
    }

    /**
     * Gets a list of locations with units at War, which are from the Side $side, within $range of enemies and if $leader is true, have a leader
     * @param Side $side
     * @param bool $leader
     * @param int $range
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocationsWithUnitsAbleToAttack(Side $side = NULL, $leader = false, $range = 1) {
        if ( !$side ) {
            $side = $this->getCurrentSide();
        }
        $locs = $this->getLocationsWithUnits($side);
        $self = $this;
        if ( $leader ) {
            $locs = $locs->filter(
                function ($l) use ($self) {
                    /** @var Unit $u */
                    foreach ($l->getUnits() as $u) {
                        if ($u->isNationAtWar() && $u->isLeader($self)) {
                            return true;
                        }
                    }
                }
            );
        }
        else {
            $locs = $locs->filter(
                function ($l) use ($self) {
                    /** @var Unit $u */
                    foreach( $l->getUnits() as $u ) {
                        if ( $u->isNationAtWar() ) {
                            return true;
                        }
                    }
                }
            );
        }
        foreach( $this->getSides() as $s ) {
            if ( $s != $side ) {
                $oppSide = $s;
            }
        }
        return $locs->filter(
            function($l) use($oppSide,$range) {
                foreach( $l->getDestinations($range) as $d ) {
                    /** @var $d Location */
                    if ( $d->hasUnitsFromSide($oppSide) ) {
                        return true;
                    }
                }
            }
        );

    }
    public function setArmyStartLocation($data){
        $this->getCurrentAction()->addData($data);
    }

    public function setArmyEndLocation($data){
        $this->getCurrentAction()->addData($data);
    }

    public function addDataToAction($data) {
        $this->getCurrentAction()->addData($data);
    }

    /**
     * @TODO Check unit stacking limit etc.
     * @param $data
     */
    public function actionMoveUnits($data) {
        $actionData = $this->getCurrentAction()->getData();
        $destination = $this->getLocations()->get($actionData['destination']->getID());
        $unitNames = array();
        /** @var Unit $u */
        foreach( $data['units'] as $u ) {
            $this->moveUnit($u,$destination);
            if ( !isset($unitNames[$u->getName()]) ) {
                $unitNames[$u->getName()] = 1;
            } else {
                $unitNames[$u->getName()]++;
            }
        }
        $units = '';
        foreach($unitNames as $key => $value ) {
            $units .= "{$value} {$key}, ";
        }
        $units = substr($units, 0, -2);
        LogFactory::setText("{$units} moved from {$actionData['source']->getName()} to {$destination->getName()}.");
    }

    /**
     * Move an array of units
     * @param $units
     * @param Location $location
     */
    public function moveUnits($units, Location $location)
    {
        foreach( $units as $u ) {
            $this->moveUnit($u,$location);
        }
    }

    /**
     * Move a single unit
     * @param Unit $unit
     * @param Location $location
     */
    public function moveUnit(Unit $unit, Location $location)
    {
        $unit->setLocation($location)->setLastMoved($this->getSubphase());
    }

    /**
     * Set actionStack
     *
     * @param \Meldon\WotRBundle\Entity\ActionStack $actionStack
     * @return Game
     */
    public function setActionStack(\Meldon\WotRBundle\Entity\ActionStack $actionStack = null)
    {
        $this->actionStack = $actionStack;

        return $this;
    }

    /**
     * Get actionStack
     *
     * @return \Meldon\WotRBundle\Entity\ActionStack 
     */
    public function getActionStack()
    {
        return $this->actionStack;
    }

    public function skipAction() {
        LogFactory::setText('Action phase skipped - discarded die.');
    }

    /**
     * Checks neither player exceeds hand size - ideally check opponent first so actions go on stack correctly
     * Can't do this while in test
     * @TODO Set order of checks
     */
    public function checkHandSize() {
        $a = $this->getCurrentAction();
        foreach( $this->getSides() as $side ) {
            if ( $this->getHand($side)->getCards()->count() > self::MAX_HAND_SIZE ) {
                $nA = new Action( $a->getNextAction(), $side );
                $this->setCurrentAction($nA);
            }
        }
    }

    /**
     * Set battle
     *
     * @param \Meldon\WotRBundle\Entity\Battle $battle
     * @return Game
     */
    public function setBattle(\Meldon\WotRBundle\Entity\Battle $battle = null)
    {
        $this->battle = $battle;
        $battle->setGame($this);
        return $this;
    }

    /**
     * Get battle
     *
     * @return \Meldon\WotRBundle\Entity\Battle 
     */
    public function getBattle()
    {
        return $this->battle;
    }

    public function removeBattle()
    {
        $this->battle = NULL;
    }

    public function setupBattle($data) {
        $battle = new Battle();
        $attacker = new Combatant($this->getCurrentSide());
        $defender = new Combatant($this->getOpponentSide());
        $setData = $this->getCurrentAction()->getData();
        $attLoc = $this->getLocations()->get($setData['source']->getID());
        $attacker->setLocation($attLoc);
        $defLoc = $this->getLocations()->get($setData['destination']->getID());
        $defender->setLocation($defLoc);
        /** @var Unit $u */
        foreach( $data['units'] as $u ) {
            $u->setCombatant($attacker);
        }
        foreach( $defLoc->getUnits() as $u ) {
            /** @var $u Unit */
            $u->setCombatant($defender);
        }
        $battle->setAttacker($attacker);
        $battle->setDefender($defender);
        $battle->setCombatValues();
        $this->setBattle($battle);
        LogFactory::setText("Battle is joined - {$this->getCurrentSide()->getName()} is attacking the {$this->getOpponentSide()->getName()} at {$battle->getDefender()->getLocation()->getName()}.");
        $this->setCurrentAction(new Action($this->getCurrentAction()->getNextAction()));
    }

    public function retreatIntoStronghold($data) {
        $battle = $this->getBattle();
        if ( $data['retreat'] ) {
            $shLoc = $battle->getDefender()->getLocation()->getStrongholdConnection();
            $this->moveUnits($data['units'],$shLoc);
            $battle->removeOutsideDefenders();
            $this->endBattle(true);
            LogFactory::setText("The defenders retreat behind the walls of {$battle->getDefender()->getLocation()->getName()}.");
         } else {
            LogFactory::setText("The defenders choose to fight a field battle.");
        }
        // Remove current action from stack
        $this->removeCurrentAction();
    }

    public function setAttackerCard($data)
    {
        if ($data['noBattleCard']) {
            LogFactory::setText("The Attacker chooses to play no battle card.");
        } else {
            $this->getBattle()->getAttacker()->setCard($data['card']);
            LogFactory::setText("The Attacker chooses to play a {$this->getBattle()->getAttackerCard()->getType()->getName()} card.");
        }
    }


    /**
     * Set defender card (if any), reveal played cards and move them to the discard piles
     * @param $data
     */
    public function setDefenderCard($data)
    {
        if ($data['noBattleCard']) {
            LogFactory::setText("The Defender chooses to play no battle card.");
        } else {
            $this->getBattle()->getDefender()->setCard($data['card']);
            LogFactory::setText("The Defender chooses to play a {$this->getBattle()->getDefenderCard()->getType()->getName()} card.");
        }
        $this->getBattle()->setCardsRevealed(true);
        $aCard = $this->getBattle()->getAttackerCard();
        if ( $aCard ) {
            $aText = $aCard->getBattleEvent()->getName();
            $this->discardCard($aCard);
        } else {
            $aText = 'no card';
        }
        $dCard = $this->getBattle()->getDefenderCard();
        if ( $dCard ) {
            $dText = $dCard->getBattleEvent()->getName();
            $this->discardCard($dCard);
        } else {
            $dText = 'nocard';
        }
        LogFactory::setText("The Attacker plays {$aText}, while the Defender plays {$dText}");
    }

    public function checkAttackerDestroyed()
    {
        $b = $this->getBattle();
        if ( $b->getAttacker()->getHits() <= $b->getDefender()->getHitsToResolve() ) {
            $b->getDefender()->setHitsToResolve(0);
            foreach( $b->getAttacker()->getUnits() as $unit ) {
                $unit->becomeCasualty();
            }
            LogFactory::setText("The Attacker's army has been wiped out.");
            if ( $b->getDefender()->getLocation()->isInStronghold() ) {
                foreach( $b->getDefender()->getUnits() as $unit ) {
                    $unit->setLocation( $b->getAttacker()->getLocation() );
                }
                LogFactory::setText("The Defenders emerge from the Stronghold; the siege broken.");
            }
        }
        $this->endBattle();
    }

    public function checkDefenderDestroyed()
    {
        $b = $this->getBattle();
        if ( $b->getDefender()->getHits() <= $b->getAttacker()->getHitsToResolve() ) {
            $b->getAttacker()->setHitsToResolve(0);
            foreach( $b->getDefender()->getUnits() as $unit ) {
                $unit->becomeCasualty();
            }
            LogFactory::setText("The Defender's army has been wiped out.");
        }
        $this->endBattle(true);
    }

    public function takeCasualties($data)
    {
        /** @var Unit $unit */
        $hits = 0;
        foreach( $data['units'] as $unit ) {
            $hits += $unit->takeHit($this);
        }
        if ( $remHits = $this->getBattle()->resolveHits($this->getCurrentSide(),$hits) > 0 ) {
            $this->getCurrentAction()->decreaseIndex();
            LogFactory::setText($remHits . " hit still to resolve.");
        }
    }

    /**
     * @TODO Reduce Elite to continue siege
     * @param $data
     */
    public function ceaseAttack($data)
    {
        if ( $data['cease'] ) {
            $this->endBattle();
            LogFactory::setText("The {$this->getBattle()->getAttacker()->getSide()->getName()} have called off the attack.");
        } else {
            LogFactory::setText("The {$this->getBattle()->getAttacker()->getSide()->getName()} are pressing the attack.");
        }
    }

    /**
     * End the Battle - if the attacker can $advance then set up the action for them to do so
     * If the defenders were defending a stronghold then take the location
     * @param bool $advance
     */
    public function endBattle($advance = false)
    {
        $this->removeCurrentAction();
        if ( $advance ) {
            if ( $this->getBattle()->getDefender()->getLocation()->isInStronghold() ) {
                $this->getBattle()->getAttacker()->getLocation()->setSide($this->getBattle()->getAttacker()->getSide());
            } else {
                $nA = new Action($this->getCurrentAction()->getNextAction());
                $this->setCurrentAction($nA);
                $this->setDecision($this->getBattle()->getAttacker()->getSide());
            }
        } else {
            $this->getBattle()->cleanup();
        }
    }

    public function retreatFromBattle($data)
    {
        if ( $data['retreat']) {
            $this->moveUnits($this->getBattle()->getDefender()->getUnits(),$data['destination']);
            LogFactory::setText("The Defender retreats to {$data['destination']->getName()}");
            $this->endBattle(true);
        }
    }

    public function attackerAdvance($data)
    {
        if ( !$data['noAdvance'] ) {
            $this->moveUnits($data['units'], $this->getBattle()->getDefender()->getLocation());
            $units = $data['units']->map(
                function ($u) {
                    return $u->getName();
                }
            );
            LogFactory::setText("The Attacker advances " . explode(', ', $units) . " to {$this->getBattle()->getDefender()->getLocation()}.");
        }
        $this->getBattle()->cleanup();
    }

    public function nextBattleRound()
    {
        $this->getBattle()->advanceRound();
        LogFactory::setText("The battle continues to another round.");
        $nA = new Action($this->getCurrentAction()->getNextAction());
        $this->setCurrentAction($nA);
    }

    public function playDeadlyStrife()
    {
        $this->getBattle()->getAttacker()->addCombatModifier(2)->addRerollModifier(2);
        $this->getBattle()->getDefender()->addCombatModifier(2)->addRerollModifier(2);
    }

    public function playServantSecretFire()
    {
        $this->getBattle()->getFP()->addCombatModifier(1);
    }

    public function reduceElite(Unit $e)
    {
        $units = $this->getUnits();
        $possRegs = $units->filter(
            function ($u) use ($e) {
                return $u->getNation() == $e->getNation()
                && is_null($u->getLocation())
                && $u->isRegular();
            }
        );
        $deadRegs = $possRegs->filter(
            function ($u) {
                return $u->getCasualty();
            }
        );
        if ( !$deadRegs->isEmpty() ) {
            $nR = $deadRegs->first();
        } else {
            $nR = $possRegs->first();
        }
        $nR->setLocation($e->getLocation())->setCombatant($e->getCombatant())->setCasualty(false);
        return $nR;
    }

    public function getUnitsBySide(Side $side)
    {
        return $this->getUnits()
            ->filter(
                function($u) use ($side) {
                    /** @var Unit $u */
                    return $u->getSide() == $side;
                }
            );
    }

    /**
     * Get a list of Minions and Nazgul on the board who have not moved this subphase
     * @param bool $moved
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMinions($moved = false)
    {
        $self = $this;
        return $this->getUnitsBySide($this->getSide('S'))
            ->filter(
                function ($u) use ($self,$moved) {
                    /** @var Unit $u */
                    return ( $moved || $u->getLastMoved() != $self->getSubPhase() ) && $u->getLocation() && ( $u->isNazgul() || ( $u->isCharacter() && $u->getLevel() != 0 ) );
                }
            );
    }

    public function moveMinion($data)
    {
        $units = '';
        $destination = $data['destination'];
        foreach( $this->getCurrentAction()->getData()['units'] as $u )
        {
            $units .= $u->getName() . ', ';
            $u = $this->getUnits()->get($u->getId());
            $this->moveUnit($u,$destination);
        }
        LogFactory::setText("{$units} have been moved to {$destination->getName()}.");
        $nA = new Action($this->getCurrentAction()->getNextAction());
        $this->setCurrentAction($nA);
    }

    /**
     * Get a list of Companions on the board who have not moved this subphase
     * @param bool $moved
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanions($moved = false)
    {
        $self = $this;
        return $this->getUnitsBySide($this->getSide('FP'))
            ->filter(
                function ($u) use ($self,$moved) {
                    /** @var Unit $u */
                    return ( $moved || $u->getLastMoved() != $self->getSubPhase() ) && $u->getLocation() && ( $u->isCharacter() && $u->getLevel() != 0 );
                }
            );
    }

    public function moveCompanions($data)
    {
        $units = '';
        $destination = $data['destination'];
        foreach( $this->getCurrentAction()->getData()['units'] as $u )
        {
            $units .= $u->getName() . ', ';
            $u = $this->getUnits()->get($u->getId());
            $this->moveUnit($u,$destination);
        }
        LogFactory::setText("{$units} have been moved to {$destination->getName()}.");
        $nA = new Action($this->getCurrentAction()->getNextAction());
        $this->setCurrentAction($nA);
    }

    public function moveCompanionsFromFellowship($data)
    {
        $units = '';
        $destination = $data['destination'];
        /** @var Character $u */
        foreach( $this->getCurrentAction()->getData()['units'] as $u )
        {
            $units .= $u->getName() . ', ';
            var_dump( $units );
            Debug::dump( $u );
            $u = $this->getUnits()->get($u->getId());
            $this->moveUnit($u,$destination);
            $u->leaveFellowship();
        }
        LogFactory::setText("{$units} have left the Fellowship and moved to {$destination->getName()}.");
    }

    public function submitMessage($data)
    {
        LogFactory::setText(htmlentities($data['message'],ENT_QUOTES | ENT_HTML5,"UTF-8"));
    }

    public function determineHuntSuccess()
    {
        if ( $this->getFellowship()->isInMordor() ) {

        } else {
            $huntDice = $this->getHuntDicePool();
            $level = $success = 0;
            $target = 6;
            /** @var ActionDie $dice */
            foreach ($huntDice as $dice) {
                /** @var ActionDieDetails $dD */
                $dD = $dice->getDetails();
                if ($dD->getSide() == $this->getSide('S')) {
                    $level++;
                } else {
                    $target--;
                }
            }
            $huntLevel = min($level, 5);
            $target = max($target, 2);
            $diceRoll = Dice::roll($huntLevel);
            foreach( $diceRoll->results as $roll ) {
                if ( $roll >= $target ) {
                    $success++;
                }
            }
            $numRerollsAvailable = $this->getHuntRerolls();
            $failedDice = $huntLevel - $success;
            if ( $failedDice && $numRerollsAvailable ) {
                $numRerolls = max($failedDice, $numRerollsAvailable);
                $diceReRoll = Dice::roll($numRerolls);
                foreach ($diceReRoll->results as $roll) {
                    if ($roll >= $target) {
                        $success++;
                    }
                }
            }
            LogFactory::setText('The Shadow rolls ' . implode(', ', $diceRoll->results) . " against a target of {$target}.");
            if ( isset($diceReRoll) && !empty($diceReRoll->results) ) {
                LogFactory::setText(count($diceReRoll->results) . " dice are rerolled (" . implode(', ', $diceReRoll->results) . ").");
            }
            if ( $success ) {
                LogFactory::setText("To give {$success} successes in the Hunt.");
                $this->getCurrentAction()->addData(['success'=>$success]);
                $nA = new Action($this->getCurrentAction()->getNextAction());
                $this->setCurrentAction($nA);
            } else {
                LogFactory::setText("The Shadow's Hunt is unsuccessful at this time.");
            }
        }
    }

    private function getHuntRerolls()
    {
        $reroll = 0;
        $fsLoc = $this->getFellowship()->getLocation();
        if ( $fsLoc->getStronghold() && $fsLoc->getSide() == $this->getSide('S') ) {
            $reroll++;
        }
        $fsUnits = $this->filterBySide($this->getUnits(),$this->getSide('S'));
        $fsUnits = $this->filterByLocation($fsUnits,$fsLoc);
        $totalUnits = $fsUnits->count();
        $nazgul = 0;
        foreach ( $fsUnits as $unit ) {
            if ( $unit->isNazgul() ) {
                $nazgul++;
            }
        }
        if ( $nazgul ) {
            $reroll++;
        }
        if ( $totalUnits > $nazgul ) {
            $reroll++;
        }
        return $reroll;
    }
    private function filterBySide(Collection $c, Side $side)
    {
        return $c->filter(
            function ($e) use ($side) {
                return $e->getSide() == $side;
            }
        );
    }
    private function filterByLocation(Collection $c, Location $l)
    {
        return $c->filter(
            function ($e) use ($l) {
                return $e->getLocation() == $l;
            }
        );
    }

    public function moveFellowship()
    {
        $this->getFellowship()->move();
    }

    public function useDiceForFellowship()
    {
        $this->getCurrentAction()->getActionDie()->setUsedForFS(true);
        // Done with the tile now
        $this->getHuntDiscard()->addToBottom($this->getHuntTileToResolveGroup()->takeFromTop());
    }

    /**
     * @return bool|Card
     */
    public function getHuntTileToResolve()
    {
        $tiles = $this->getHuntTileToResolveGroup();
        return $tiles->getTopCard();
    }

    public function getHuntPrevent()
    {
        return false;
    }

    public function getHuntRedraw()
    {
        return false;
    }

    public function drawHuntTile()
    {
        $tile = $this->getHuntPool()->takeFromTop();
        $this->getHuntTileToResolveGroup()->addToBottom($tile);
        LogFactory::setText("{$tile->getName()} drawn from the bag.");
    }

    /**
     * Takes a tile and returns the damage caused
     * @param Card $tile
     * @return int
     */
    public function getHuntTileDamage(Card $tile)
    {
        $base = $tile->getCorruption();
        if ( $base == 'd' ) {
            $corruption = Dice::roll(1)->results[1];
        } elseif ( $base == 'e' ) {
            if ( $this->getFellowship()->isInMordor() ) {
                $corruption = $this->getHuntDicePool()->count();
            } else {
                $corruption = $this->getCurrentAction()->getData()['success'];
            }
        } else {
            $corruption = $base;
        }
        return $corruption;
    }

    public function resolveHuntTile($data)
    {
        $corruption = $data['huntDamage'];
        if ( $data['huntResolve'] == 'ring' ) {
            $this->getFellowship()->addCorruption($corruption);
            LogFactory::setText("Frodo uses the ring, suffering {$corruption} corruption, bringing him to {$this->getFellowship()->getCorruption()}.");
        } else {
            if ( $data['huntResolve'] == 'guide' ) {
                $companion = $this->getFellowship()->getGuide();
            } else {
                $companion = $this->getFellowship()->getRandomCompanion();
            }
            $level = $companion->getLevel();
            $mitigation = min($corruption, $level);
            $corruption -= $mitigation;
            if ($corruption == 0) {
                LogFactory::setText("{$companion->getName()} falls defending the Fellowship, preventing all corruption.");
            } else {
                $this->getFellowship()->addCorruption($corruption);
                LogFactory::setText("{$companion->getName()} falls defending the Fellowship, preventing {$mitigation} corruption.");
                LogFactory::setText("Frodo uses the ring, suffering {$corruption} corruption, bringing him to {$this->getFellowship()->getCorruption()}.");
            }
            $companion->becomeCasualty();
            if ( !$this->getFellowship()->getGuide() ) {
                $this->getCurrentAction()->setNextAction($this);
            }
        }

    }

    /**
     * @TODO Added starts and ends in stronghold, need to add through...possibly have a get destinations but not accepting strongholds...
     * @param $data
     */
    public function resolveRevealFellowship($data)
    {
        $extraTiles = 0;
        $fsLoc = $this->getFellowship()->getLocation();
        if ( $fsLoc->getSide() && $fsLoc->getSide()->isShadow() && $fsLoc->getStronghold() ) {
            $extraTiles++;
        }
        $this->revealFellowship();
        $this->getFellowship()->setProgress(0);
        $this->getFellowship()->setLocation($data['location']);
        LogFactory::setText("The Fellowship have been revealed in {$this->getFellowship()->getLocation()->getName()}.");
        $fsLoc = $this->getFellowship()->getLocation();
        if ( $fsLoc->getSide() && $fsLoc->getSide()->isShadow() && $fsLoc->getStronghold() ) {
            $extraTiles++;
        }
        // Done with the tile now
        $this->getHuntDiscard()->addToBottom($this->getHuntTileToResolveGroup()->takeFromTop());
        for ( $i = 0; $i < $extraTiles; $i++ ) {
            $this->getCurrentAction()->setNextAction($this);
        }
        if ( $extraTiles ) {
            LogFactory::setText("The Fellowship must evade {$extraTiles} extra tiles due to being revealed in Strongholds controlled by the Shadow.");
        }
    }

    public function getLocationsToRecruit(Side $side)
    {
        $nations = $this->canRecruit();
        $locs = $this->getLocations()->filter(
            function ($l) use ($nations, $side) {
                /** @var LocationDetails $l */
                return $l->hasSettlement() && $l->getSide() == $side && in_array($l->getNationDetails(),$nations);
            }
        );
        return $locs;
    }

    public function getUnitsToRecruit(Location $l, $elite = true)
    {
        $units = $this->getUnits(true)->filter(
            function ($u) use ($l) {
                return $u->getNation() && $u->getNation()->getDetails() == $l->getNation();
            }
        );
        $types = array();
        foreach( $units as $u ) {
            if ( in_array($u->getType(), $types ) || ( !$elite && $u->isElite() ) ) {
                $units->removeElement($u);
            } else {
                $types[] = $u->getType();
            }
        }
        return $units;
    }

    public function musterFirstUnit($data)
    {
        $l = $this->getLocations()->get($this->getCurrentAction()->getData()['firstLocation']->getID());
        /** @var Unit $u */
        $u = $data['unit'];
        $u->setLocation($l);
        LogFactory::setText("A {$u->getName()} has been mustered in {$l->getName()}.");
        if ( $u->isElite() ) {
            $this->removeCurrentAction();
        }
    }

    public function musterSecondUnit($data)
    {
        $l = $this->getLocations()->get($this->getCurrentAction()->getData()['secondLocation']->getID());
        /** @var Unit $u */
        $u = $data['unit'];
        $u->setLocation($l);
        LogFactory::setText("A {$u->getName()} has been mustered in {$l->getName()}.");
    }

    public function getMinionsToRecruit()
    {
        return $this->filterBySide($this->getCharacters(),$this->getSide('S'))->filter(
            function ($c) {
                return $c->canBeMustered();
            }
        );
    }

    public function getCharactersToRecruit()
    {
        return $this->filterBySide($this->getCharacters(),$this->getSide('FP'))->filter(
            function ($c) {
                return $c->canBeMustered();
            }
        );
    }

    /**
     * Set nations
     *
     * @param \Meldon\WotRBundle\Entity\NationCollection $nations
     * @return Game
     */
    public function setNations(\Meldon\WotRBundle\Entity\NationCollection $nations = null)
    {
        $this->nations = $nations;

        return $this;
    }
}
