<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 19/05/2015
 * Time: 08:32
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.cardevent")
 */
class CardEvent {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="EventCard", inversedBy="events")
     */
    private $cardDetails;
    /**
     * @ORM\Column(type="integer")
     */
    private $eventOrder;
    /**
     * @ORM\OneToMany(targetEntity="EventPrerequisiteGroup", mappedBy="cardEvent")
     * @ORM\OrderBy({"groupNum" = "ASC"})
     */
    private $prereqGroups;
    /**
     * @ORM\ManyToOne(targetEntity="CardEventDetails")
     */
    private $eventDetails;

    /**
     * @param $data
     * @param Card $card
     * @return mixed
     * @throws EventMethodNotDefinedException
     */
    public function execute($data, Card $card)
    {
        if ( $this->isAvailable($data, $card) )
        {
            $methods = $this->getEventDetails()->parseMethods();
            $arguments = $this->getEventDetails()->parseArguments($card, $methods);
            foreach ($methods as $methodNum => $method) {
                if (method_exists($data, $method)) {
                    call_user_func_array(array($data, $method), $arguments[$methodNum]);
                } else {
                    throw new EventMethodNotDefinedException;
                }
            }
        }
        return $data;
    }

    /**
     * Go through the groups - return false if any are not fulfilled (AND comparison)
     * @param $data
     * @return bool
     */
    public function isAvailable($data)
    {
        /* @var $prG EventPreRequisiteGroup */
        foreach( $this->getPreReqGroups() as $prG )
        {
            if ( !$prG->assessGroup($data) )
                return false;
        }
        return true;
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
     * Constructor
     */
    public function __construct()
    {
        $this->preReqGroups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set cardDetails
     *
     * @param \Meldon\WotRBundle\Entity\EventCard $cardDetails
     * @return CardEvent
     */
    public function setCardDetails(\Meldon\WotRBundle\Entity\EventCard $cardDetails = null)
    {
        $this->cardDetails = $cardDetails;

        return $this;
    }

    /**
     * Get cardDetails
     *
     * @return \Meldon\WotRBundle\Entity\EventCard 
     */
    public function getCardDetails()
    {
        return $this->cardDetails;
    }

    /**
     * Set eventDetails
     *
     * @param \Meldon\WotRBundle\Entity\CardEventDetails $eventDetails
     * @return CardEvent
     */
    public function setEventDetails(\Meldon\WotRBundle\Entity\CardEventDetails $eventDetails = null)
    {
        $this->eventDetails = $eventDetails;

        return $this;
    }

    /**
     * Get eventDetails
     *
     * @return \Meldon\WotRBundle\Entity\CardEventDetails 
     */
    public function getEventDetails()
    {
        return $this->eventDetails;
    }

    /**
     * Set eventOrder
     *
     * @param integer $eventOrder
     * @return CardEvent
     */
    public function setEventOrder($eventOrder)
    {
        $this->eventOrder = $eventOrder;

        return $this;
    }

    /**
     * Get eventOrder
     *
     * @return integer 
     */
    public function getEventOrder()
    {
        return $this->eventOrder;
    }

    /**
     * Add prereqGroup
     *
     * @param \Meldon\WotRBundle\Entity\EventPrerequisiteGroup $prereqGroup
     * @return CardEvent
     */
    public function addPrereqGroups(\Meldon\WotRBundle\Entity\EventPrerequisiteGroup $prereqGroup)
    {
        $this->prereqGroups[] = $prereqGroup;

        return $this;
    }

    /**
     * Remove prereqGroup
     *
     * @param \Meldon\WotRBundle\Entity\EventPrerequisiteGroup $prereqGroup
     */
    public function removePrereqGroups(\Meldon\WotRBundle\Entity\EventPrerequisiteGroup $prereqGroup)
    {
        $this->prereqGroups->removeElement($prereqGroup);
    }

    /**
     * Get prereqGroup
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrereqGroups()
    {
        return $this->prereqGroups;
    }

    /**
     * Add prereqGroups
     *
     * @param \Meldon\WotRBundle\Entity\EventPrerequisiteGroup $prereqGroups
     * @return CardEvent
     */
    public function addPrereqGroup(\Meldon\WotRBundle\Entity\EventPrerequisiteGroup $prereqGroups)
    {
        $this->prereqGroups[] = $prereqGroups;

        return $this;
    }

    /**
     * Remove prereqGroups
     *
     * @param \Meldon\WotRBundle\Entity\EventPrerequisiteGroup $prereqGroups
     */
    public function removePrereqGroup(\Meldon\WotRBundle\Entity\EventPrerequisiteGroup $prereqGroups)
    {
        $this->prereqGroups->removeElement($prereqGroups);
    }
}
