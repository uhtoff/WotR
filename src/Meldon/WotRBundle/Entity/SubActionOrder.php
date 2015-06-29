<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 21/05/2015
 * Time: 21:54
 */

namespace Meldon\WotRBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subActionOrder")
 */
 
class SubActionOrder  {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var SubAction
     * @ORM\ManyToOne(targetEntity="SubAction")
     *
     */
    private $subAction;
    /**
     * @ORM\Column(type="integer")
     */
    private $subActionOrder;
    /**
     * @var Action
     * @ORM\ManyToOne(targetEntity="ActionDetails", inversedBy="subActionOrder")
     */
    private $actionDetails;

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
     * Set subActionOrder
     *
     * @param integer $subActionOrder
     * @return SubActionOrder
     */
    public function setSubActionOrder($subActionOrder)
    {
        $this->subActionOrder = $subActionOrder;

        return $this;
    }

    /**
     * Get subActionOrder
     *
     * @return integer 
     */
    public function getSubActionOrder()
    {
        return $this->subActionOrder;
    }

    /**
     * Set subAction
     *
     * @param \Meldon\WotRBundle\Entity\subAction $subAction
     * @return SubActionOrder
     */
    public function setSubAction(\Meldon\WotRBundle\Entity\subAction $subAction = null)
    {
        $this->subAction = $subAction;

        return $this;
    }

    /**
     * Get subAction
     *
     * @return \Meldon\WotRBundle\Entity\subAction 
     */
    public function getSubAction()
    {
        return $this->subAction;
    }

    /**
     * Set actionDetails
     *
     * @param \Meldon\WotRBundle\Entity\ActionDetails $actionDetails
     * @return SubActionOrder
     */
    public function setActionDetails(\Meldon\WotRBundle\Entity\ActionDetails $actionDetails = null)
    {
        $this->actionDetails = $actionDetails;

        return $this;
    }

    /**
     * Get actionDetails
     *
     * @return \Meldon\WotRBundle\Entity\ActionDetails 
     */
    public function getActionDetails()
    {
        return $this->actionDetails;
    }
}
