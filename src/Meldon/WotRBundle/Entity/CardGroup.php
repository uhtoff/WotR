<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Debug;
use Doctrine\Common\Collections\Criteria;
use Meldon\WotRBundle\Entity\Card;

/**
 * Description of CardGroup
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="CardGroupRepository")
 * @ORM\Table(name="wotrnew.cardgroup")
 */
class CardGroup {
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
     * @ORM\ManyToOne(targetEntity="CardGroupDetails", fetch="EAGER")
     */
    private $details;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Game",inversedBy="cardGroups")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    /**
     *
     * @ORM\OneToMany(targetEntity="Card",mappedBy="group", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $cards;

    /**
     * Constructor
     * @param CardGroupDetails $details
     * @param Game $game
     */
    public function __construct(CardGroupDetails $details = NULL, Game $game = NULL)
    {
        if ( $details ) $this->setDetails($details);
        if ( $game ) $this->setGame ($game);
        $this->cards = new ArrayCollection();
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
     * @return CardGroup
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->addCardGroup($this);
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
     * Add cards
     *
     * @param \Meldon\WotRBundle\Entity\Card $cards
     * @return CardGroup
     */
    public function addCard(\Meldon\WotRBundle\Entity\Card $cards)
    {
        $this->cards[] = $cards;

        return $this;
    }

    /**
     * Remove cards
     *
     * @param \Meldon\WotRBundle\Entity\Card $cards
     */
    public function removeCard(\Meldon\WotRBundle\Entity\Card $cards)
    {
        $this->cards->removeElement($cards);
    }

    /**
     * Get cards
     *
     * @return ArrayCollection
     */
    public function getCards()
    {
        // get a new ArrayIterator
        $iterator = $this->cards->getIterator();

        // define ordering closure, using preferred comparison method/field
        $iterator->uasort(function ($first, $second) {
            return (int) $first->getPosition() > (int) $second->getPosition() ? 1 : -1;
        });

        // return the ordered iterator
        return $this->cards = new ArrayCollection(iterator_to_array($iterator));
    }
    /**
     * Map through array collection getting positions of all cards
     * @return integer[]
     */
    public function getPositions() 
    {
        return $this->getCards()
                ->map(function($card){return $card->getPosition();})->toArray();
    }
    /**
     * Shuffles card group
     */
    public function shuffle() 
    {        
        $oldPositions = $positions = $this->getPositions();
        shuffle($positions);
        $newPos = array_combine($oldPositions, $positions);
        foreach( $this->getCards() as $card )
        {
            $card->setPosition($newPos[$card->getPosition()]);
        }
    }
    /**
     * Returns and removes the top card from the array
     * @return Card|boolean
     */
    public function takeFromTop() 
    {
        $card = $this->getCards()->first();
        if ( $card !== false )
        {
            $card->removeGroup($this);
            return $card;
        } else 
        {
            return false;
        }
    }
    /**
     * Returns and removes the bottom card from the array
     * @return Card|boolean
     */
    public function takeFromBottom() 
    {
        $card = $this->getCards()->last();
        if ( $card !== false )
        {
            $card->removeGroup($this);
            return $card;
        } else {
            return false;
        }
    }
    public function getTopCard()
    {
        $card = $this->getCards()->first();
        if ( $card !== false )
        {
            return $card;
        } else
        {
            return false;
        }
    }
    /**
     * Adds a card to the top of the deck, shuffles down the positions if necessary
     * @param \Meldon\WotRBundle\Entity\Card $card
     * @return \Meldon\WotRBundle\Entity\Card
     */
    public function addToTop(Card $card)
    {
        $pos = $this->getPositions();
        // If card group is empty
        if ( count($pos) != 0 ) {
            $topPos = min($this->getPositions());        
            if ( $topPos == 1 ) {
                $this->getCards()
                        ->map(function($card){$card->setPosition($card->getPosition() + 1);});
            }
        }        
        $card->setPosition(1);
        $card->setGroup($this);
        return $card;
    }
    /**
     * Adds a card to the bottom of the deck
     * @param \Meldon\WotRBundle\Entity\Card $card
     * @return \Meldon\WotRBundle\Entity\Card
     */
    public function addToBottom(Card $card) {
        $pos = $this->getPositions();
        if ( count($pos) == 0 )
        {
            $bottomPos = 0;
        } else 
        {
            $bottomPos = max($pos);
        }
        $card->setPosition($bottomPos + 1);
        $card->setGroup($this);
        return $card;
    }
    /**
     * Moves all the cards back to positions starting from 1
     */
    public function consolidate()
    {
        $oldPositions = $this->getPositions();
        $positions = range(1, count($oldPositions));
        $newPos = array_combine($oldPositions, $positions);
        foreach( $this->getCards() as $card )
        {
            $card->setPosition($newPos[$card->getPosition()]);
        }
    }
    /**
     * 
     * @param \Meldon\WotRBundle\Entity\CardGroup $group
     * @param boolean $shuffle
     * @return \Meldon\WotRBundle\Entity\CardGroup
     */
    public function moveAllTo(CardGroup $group, $shuffle = false)
    {
        while( $card = $this->takeFromTop() )
        {
            $group->addToBottom($card);
        }
        $group->consolidate();
        if ( $shuffle )
        {
            $group->shuffle();
        }
        return $group;
    }

    /**
     * Set details
     *
     * @param \Meldon\WotRBundle\Entity\CardGroupDetails $details
     * @return CardGroup
     */
    public function setDetails(\Meldon\WotRBundle\Entity\CardGroupDetails $details = null)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return \Meldon\WotRBundle\Entity\CardGroupDetails 
     */
    public function getDetails()
    {
        return $this->details;
    }
    
    public function numCards($type = NULL,$checkPlayable = false)
    {
        return $this->getCardsByType($type,$checkPlayable)->count();
    }

    /**
     * Return a collection of all the cards of a particular type
     * Can just provide playable cards
     * @param null $type
     * @param bool $checkPlayable
     * @return Card[]
     */
    public function getCardsByType($type = NULL,$checkPlayable = false)
    {
        if ( $type )
        {
            $cards = $this->getCards()->filter(
                function ($e) use ($type)
                {
                /** @var $e EventCard */
                    return $e->isCardType($type);
                }
            );
        } else
        {
            $cards = $this->getCards();
        }
        $game = $this->getGame();
        if ( $checkPlayable )
        {
            $cards = $cards->filter(
                function ($e) use ($game)
                {
                    /** @var $e EventCard */
                    return $e->isPlayable($game);
                }
            );
        }
        return $cards;
    }

    /**
 * Return first card with the given detailsID
 * @param $d integer
 * @return Card
 */
    public function getCardByDetailsID($d)
    {
        return $cards = $this->getCards()->filter(
            function ($e) use ($d)
            {
                /** @var $e Card */
                return $e->getDetailsID() == $d;
            }
        )->first();
    }

    /**
     * Return all cards with the given detailsID
     * @param $d integer
     * @return Collection
     */
    public function getCardsByDetailsID($d)
    {
        return $cards = $this->getCards()->filter(
            function ($e) use ($d)
            {
                /** @var $e Card */
                return $e->getDetailsID() === $d;
            }
        )->first();
    }

    public function getCardRoute($type, $identifier=NULL)
    {
        $discard = $this->getDetails()->getMappings()
                ->filter(
                    function($e) use ($type)
                    {
                        return $e->getType() == $type;
                    });
        if ( $identifier )
        {
            $discard = $discard->filter(
                    function($e) use ($identifier)
                    {
                        return $e->getIdentifier() == $identifier;
                    });
        } else 
        {
            $discard = $discard->filter(
                    function($e)
                    {
                        return $e->getDefault();
                    });
        }
        return $discard->first();
    }
    
   public function getDiscardRoute($identifier = NULL)
   {
       return $this->getCardRoute('discard',$identifier);
   }
   
   public function getDrawRoute($identifier = NULL)
   {
       return $this->getCardRoute('draw',$identifier);
   }
}
