<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 19/05/2015
 * Time: 13:54
 */

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pre-requisite group so we can have an 'OR' in our prerequisites
 * @ORM\Entity
 * @ORM\Table(name="eventprerequisitegroup")
 */
 
class EventPrerequisiteGroup  {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $groupNum = 1;
    /**
     * @ORM\ManyToMany(targetEntity="EventPreRequisite")
     */
    private $prereq;
    /**
     * @ORM\ManyToOne(targetEntity="CardEvent", inversedBy="prereqGroups")
     */
    private $cardEvent;

    public function assessGroup($data)
    {
        foreach( $this->getPrereq() as $pr )
        {
            /** @var $pr PreRequisite */
            if ( $pr->assess($data) )
            {
                return true;
            }
        }
        return false;
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
     * Set groupNum
     *
     * @param integer $groupNum
     * @return EventPrerequisiteGroup
     */
    public function setGroupNum($groupNum)
    {
        $this->groupNum = $groupNum;

        return $this;
    }

    /**
     * Get groupNum
     *
     * @return integer 
     */
    public function getGroupNum()
    {
        return $this->groupNum;
    }

    /**
     * Set cardEvent
     *
     * @param \Meldon\WotRBundle\Entity\CardEvent $cardEvent
     * @return EventPrerequisiteGroup
     */
    public function setCardEvent(\Meldon\WotRBundle\Entity\CardEvent $cardEvent = null)
    {
        $this->cardEvent = $cardEvent;
        $cardEvent->addPrereqGroup($this);
        return $this;
    }

    /**
     * Get cardEvent
     *
     * @return \Meldon\WotRBundle\Entity\CardEvent 
     */
    public function getCardEvent()
    {
        return $this->cardEvent;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prereq = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add prereq
     *
     * @param \Meldon\WotRBundle\Entity\EventPreRequisite $prereq
     * @return EventPrerequisiteGroup
     */
    public function addPrereq(\Meldon\WotRBundle\Entity\EventPreRequisite $prereq)
    {
        $this->prereq[] = $prereq;

        return $this;
    }

    /**
     * Remove prereq
     *
     * @param \Meldon\WotRBundle\Entity\EventPreRequisite $prereq
     */
    public function removePrereq(\Meldon\WotRBundle\Entity\EventPreRequisite $prereq)
    {
        $this->prereq->removeElement($prereq);
    }

    /**
     * Get prereq
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrereq()
    {
        return $this->prereq;
    }
}
