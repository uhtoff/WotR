<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 20/05/2015
 * Time: 22:50
 */

namespace Meldon\WotRBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="actionPrerequisiteGroup")
 */
 
class ActionPrerequisiteGroup  {
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
     * @ORM\ManyToMany(targetEntity="ActionPreRequisite")
     */
    private $preReq;
    /**
     * @ORM\ManyToOne(targetEntity="SubAction", inversedBy="prereqGroups")
     */
    private $subAction;

    /**
     * Group assessed on an OR basis
     * @param $data
     * @return bool
     */
    public function assessGroup($data)
    {
        /** @var PreRequisite $pr */
        foreach( $this->getPrereq() as $pr )
        {
            if ( $pr->assess($data) )
            {
                return true;
            }
        }
        return false;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->preReq = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ActionPrerequisiteGroup
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
     * Add preReq
     *
     * @param \Meldon\WotRBundle\Entity\ActionPreRequisite $preReq
     * @return ActionPrerequisiteGroup
     */
    public function addPreReq(\Meldon\WotRBundle\Entity\ActionPreRequisite $preReq)
    {
        $this->preReq[] = $preReq;

        return $this;
    }

    /**
     * Remove preReq
     *
     * @param \Meldon\WotRBundle\Entity\ActionPreRequisite $preReq
     */
    public function removePreReq(\Meldon\WotRBundle\Entity\ActionPreRequisite $preReq)
    {
        $this->preReq->removeElement($preReq);
    }

    /**
     * Get preReq
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPreReq()
    {
        return $this->preReq;
    }

    /**
     * Set subAction
     *
     * @param \Meldon\WotRBundle\Entity\SubAction $subAction
     * @return ActionPrerequisiteGroup
     */
    public function setSubAction(\Meldon\WotRBundle\Entity\SubAction $subAction = null)
    {
        $this->subAction = $subAction;
        $subAction->addPrereqGroup($this);
        return $this;
    }

    /**
     * Get subAction
     *
     * @return \Meldon\WotRBundle\Entity\SubAction 
     */
    public function getSubAction()
    {
        return $this->subAction;
    }
}
