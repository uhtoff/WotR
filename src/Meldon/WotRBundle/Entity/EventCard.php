<?php
namespace Meldon\WotRBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of EventCard
 *
 * @author Russell
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.eventcard")
 */
class EventCard extends CardDetails {
    /**
     *
     * @ORM\Column(type="integer")
     */
    private $cardID;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     *
     * @ORM\Column(type="text")
     */
    private $cardText;
    /**
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $prereqText;
    /**
     *
     * @ORM\ManyToOne(targetEntity="EventCardType")
     */
    private $type;
    /**
     *
     * @ORM\ManyToOne(targetEntity="BattleEvent")
     */
    private $battleEvent;
    /**
     * @ORM\OneToMany(targetEntity="CardEvent", mappedBy="cardDetails")
     * @ORM\OrderBy({"eventOrder"="ASC"})
     */
    private $events;
    /**
     * Set cardID
     *
     * @param integer $cardID
     * @return EventCard
     */
    public function setCardID($cardID)
    {
        $this->cardID = $cardID;

        return $this;
    }

    /**
     * Get cardID
     *
     * @return integer 
     */
    public function getCardID()
    {
        return $this->cardID;
    }

    /**
     * Set cardText
     *
     * @param string $cardText
     * @return EventCard
     */
    public function setCardText($cardText)
    {
        $this->cardText = $cardText;

        return $this;
    }

    /**
     * Get cardText
     *
     * @return string 
     */
    public function getCardText()
    {
        return $this->cardText;
    }

    /**
     * Set prereqText
     *
     * @param string $prereqText
     * @return EventCard
     */
    public function setPrereqText($prereqText)
    {
        $this->prereqText = $prereqText;

        return $this;
    }

    /**
     * Get prereqText
     *
     * @return string 
     */
    public function getPrereqText()
    {
        return $this->prereqText;
    }

    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return EventCard
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
     * @param \Meldon\WotRBundle\Entity\EventCardType $type
     * @return EventCard
     */
    public function setType(\Meldon\WotRBundle\Entity\EventCardType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Meldon\WotRBundle\Entity\EventCardType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns true if the card is of the specified type
     * @param $type string
     * @return boolean
     */
    public function isCardType($type)
    {
        return $this->getType()->getName() === $type;
    }

    /**
     * Set battleEvent
     *
     * @param \Meldon\WotRBundle\Entity\BattleEvent $battleEvent
     * @return EventCard
     */
    public function setBattleEvent(\Meldon\WotRBundle\Entity\BattleEvent $battleEvent = null)
    {
        $this->battleEvent = $battleEvent;

        return $this;
    }

    /**
     * Get battleEvent
     *
     * @return \Meldon\WotRBundle\Entity\BattleEvent 
     */
    public function getBattleEvent()
    {
        return $this->battleEvent;
    }

    /**
     * If event card has no events attached or if one event is playable then return true
     * Otherwise return false
     * @TODO Remove no attached events clause
     * If all events are governed by one pre-req this will need to be assessed for every event
     * @param Game $game
     * @return bool
     */
    public function isPlayable(Game $game)
    {
        if ( !$this->getEvents()->isEmpty() )
        {
            foreach ($this->getEvents() as $e) {
                /** @var $e CardEvent */
                if ($e->isAvailable($game)) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Play the events on the card
     * @param Game $game
     * @param Card $card
     * @return Game
     * @throws EventMethodNotDefinedException
     */
    public function playEvent(Game $game, Card $card)
    {
        foreach( $this->getEvents() as $e )
        {
            /** @var $e CardEvent */
            $e->execute($game, $card);
        }
        return $game;
    }


    /**
     * Add events
     *
     * @param \Meldon\WotRBundle\Entity\CardEvent $events
     * @return EventCard
     */
    public function addEvent(\Meldon\WotRBundle\Entity\CardEvent $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Meldon\WotRBundle\Entity\CardEvent $events
     */
    public function removeEvent(\Meldon\WotRBundle\Entity\CardEvent $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function getCardType() {
        return 'Event';
    }

    public function getPopover()
    {
        $popover = '<a tabindex="0" data-toggle="popover" data-html="true" title="';
        $popover .= htmlentities($this->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '" data-content="<p><i>';
        $popover .= htmlentities($this->getPrereqText(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</i></p><p>';
        $popover .= htmlentities($this->getCardText(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p><p><strong>';
        $popover .= htmlentities($this->getBattleEvent()->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</strong></p><p>';
        $popover .= htmlentities($this->getBattleEvent()->getBattleText(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p><p>Initiative ';
        $popover .= htmlentities($this->getBattleEvent()->getInitiative(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p>">';
        $popover .= htmlentities($this->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</a>';
        return $popover;
    }

    public function getBattlePopover()
    {
        $popover = '<a tabindex="0" data-toggle="popover" data-html="true" title="';
        $popover .= htmlentities($this->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '" data-content="<p><i>';
        $popover .= htmlentities($this->getPrereqText(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</i></p><p>';
        $popover .= htmlentities($this->getCardText(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p><p><strong>';
        $popover .= htmlentities($this->getBattleEvent()->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</strong></p><p>';
        $popover .= htmlentities($this->getBattleEvent()->getBattleText(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p><p>Initiative ';
        $popover .= htmlentities($this->getBattleEvent()->getInitiative(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p>">';
        $popover .= htmlentities($this->getBattleEvent()->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= ' (';
        $popover .= htmlentities($this->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= ')';
        $popover .= '</a>';
        return $popover;
    }

    public function hasInitiative($i)
    {
        if ( $this->getBattleEvent()->getInitiative() == $i ) {
            return true;
        } else {
            return false;
        }
    }
}
