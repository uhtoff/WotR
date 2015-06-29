<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActionDetails
 * @author Russ
 * @ORM\Entity(repositoryClass="ActionDetailsRepository")
 * @ORM\Table(name="actionDetails")
 */
class ActionDetails
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(length=50)
     */
    private $name;
    /**
     * @ORM\Column(name="actionText", type="text")
     */
    private $text;
    /**
     * @ORM\ManyToMany(targetEntity="ActionDieDetails", inversedBy="actions")
     */
    private $actionDieDetails;
    /**
     * @var subActionOrder
     * @ORM\OneToMany(targetEntity="SubActionOrder", mappedBy="actionDetails", indexBy="subActionOrder")
     * @ORM\OrderBy({"subActionOrder" = "ASC"})
     */
    private $subActionOrder;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actionDieDetails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subActionOrder = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return ActionDetails
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return ActionDetails
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add actionDieDetails
     *
     * @param \Meldon\WotRBundle\Entity\ActionDieDetails $actionDieDetails
     * @return ActionDetails
     */
    public function addActionDieDetail(\Meldon\WotRBundle\Entity\ActionDieDetails $actionDieDetails)
    {
        $this->actionDieDetails[] = $actionDieDetails;

        return $this;
    }

    /**
     * Remove actionDieDetails
     *
     * @param \Meldon\WotRBundle\Entity\ActionDieDetails $actionDieDetails
     */
    public function removeActionDieDetail(\Meldon\WotRBundle\Entity\ActionDieDetails $actionDieDetails)
    {
        $this->actionDieDetails->removeElement($actionDieDetails);
    }

    /**
     * Get actionDieDetails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActionDieDetails()
    {
        return $this->actionDieDetails;
    }

     public function getPopover()
    {
        $popover = '<a tabindex="0" data-toggle="popover" data-html="true" title="';
        $popover .= htmlentities($this->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '" data-content="<p>';
        $popover .= htmlentities($this->getText(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p>">';
        $popover .= htmlentities($this->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</a>';
        return $popover;
    }

    /**
     * Add subActionOrder
     *
     * @param \Meldon\WotRBundle\Entity\SubActionOrder $subActionOrder
     * @return ActionDetails
     */
    public function addSubActionOrder(\Meldon\WotRBundle\Entity\SubActionOrder $subActionOrder)
    {
        $this->subActionOrder[] = $subActionOrder;

        return $this;
    }

    /**
     * Remove subActionOrder
     *
     * @param \Meldon\WotRBundle\Entity\SubActionOrder $subActionOrder
     */
    public function removeSubActionOrder(\Meldon\WotRBundle\Entity\SubActionOrder $subActionOrder)
    {
        $this->subActionOrder->removeElement($subActionOrder);
    }

    /**
     * Get subActionOrder
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubActionOrder()
    {
        return $this->subActionOrder;
    }

    /**
     * The action is available as long as the first subAction is available
     * @TODO Remove no subaction clause - ?move prereq to action itself
     * @param Game $game
     * @return bool
     */
    public function isAvailable(Game $game)
    {
        if ( !$this->getSubActionOrder()->isEmpty() )
        {
            if ( $this->getSubActionOrder()->first()->getSubAction()->isAvailable($game) ) {
                return true;
            } else {
                return false;
            }
//            foreach ($this->getsubActionOrder() as $sAO) {
//                /** @var $sAO SubActionOrder */
//                if (!$sAO->getSubAction()->isAvailable($game)) {
//                    return false;
//                }
//            }
//            return true;
        }
        return true;
    }

}
