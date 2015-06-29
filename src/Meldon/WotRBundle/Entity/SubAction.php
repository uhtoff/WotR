<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of SubAction
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.subaction")
 */
class SubAction {

    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToMany(targetEntity="ActionPrerequisiteGroup", mappedBy="subAction")
     * @ORM\OrderBy({"groupNum" = "ASC"})
     */
    private $prereqGroups;
    /**
     * @ORM\Column(length=100, nullable=true)
     */
    private $actionMethod;
    /**
     * @ORM\Column(length=100, nullable=true)
     */
    private $formType;
    /**
     * @ORM\Column(length=10, nullable=true)
     */
    private $defaultSide;
    /**
     * @ORM\ManyToOne(targetEntity="ActionDetails")
     */
    private $nextActionDetails;

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
     * Set actionMethod
     *
     * @param string $actionMethod
     * @return SubAction
     */
    public function setActionMethod($actionMethod)
    {
        $this->actionMethod = $actionMethod;

        return $this;
    }

    /**
     * Get actionMethod
     *
     * @return string 
     */
    public function getActionMethod(Game $game)
    {
        if ( $this->actionMethod === 'AttackerCard' ) {
            return $game->getBattle()->getAttackerCard()->getBattleEvent()->getActionMethod($game);
        } elseif ( $this->actionMethod === 'DefenderCard' ) {
            return $game->getBattle()->getDefenderCard()->getBattleEvent()->getActionMethod($game);
        } else {
            return $this->actionMethod;
        }
    }

    /**
     * Go through the groups - return false if any are not fulfilled (AND comparison) otherwise true (including having no prereq)
     * @param $data
     * @return bool
     */
    public function isAvailable(Game $data)
    {
        /* @var $prG ActionPreRequisiteGroup */
        foreach( $this->getPreReqGroups() as $prG )
        {
            if ( !$prG->assessGroup($data) )
                return false;
        }
        return true;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->preReqGroups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * To create an ad hoc SubAction to add to a Decision
     * @param $type string
     */
    public function create($type){
        $this->setFormType($type);
        $this->setActionMethod($type);
    }
    /**
     * Add prereqGroups
     *
     * @param \Meldon\WotRBundle\Entity\ActionPrerequisiteGroup $prereqGroups
     * @return SubAction
     */
    public function addPrereqGroup(\Meldon\WotRBundle\Entity\ActionPrerequisiteGroup $prereqGroups)
    {
        $this->prereqGroups[] = $prereqGroups;

        return $this;
    }

    /**
     * Remove prereqGroups
     *
     * @param \Meldon\WotRBundle\Entity\ActionPrerequisiteGroup $prereqGroups
     */
    public function removePrereqGroup(\Meldon\WotRBundle\Entity\ActionPrerequisiteGroup $prereqGroups)
    {
        $this->prereqGroups->removeElement($prereqGroups);
    }

    /**
     * Get prereqGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrereqGroups()
    {
        return $this->prereqGroups;
    }

    /**
     * Takes the current game and a data parameter, this can either be an object to call the action Method on, or data
     * array to send as the only argument to the actionMethod within Game
     * @param Game $game
     * @param $data
     * @return mixed
     * @throws EventMethodNotDefinedException
     */
    public function execute(Game $game, $data)
    {
        if ( $this->isAvailable($game) )
        {
            if (method_exists($data, $this->getActionMethod($game))) {
                call_user_func_array(array($data, $this->getActionMethod($game)), array($game));
            } elseif (method_exists($game, $this->getActionMethod($game) )) {
                call_user_func_array(array($game, $this->getActionMethod($game)), array($data));
            } else {
                var_dump($this->getActionMethod($game));
                throw new EventMethodNotDefinedException($this->getActionMethod($game));
            }
        }
        return $data;
    }

    /**
     * Set formType
     *
     * @param string $formType
     * @return SubAction
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;

        return $this;
    }

    /**
     * Get formType
     *
     * @return string 
     */
    public function getFormType(Game $game)
    {
        if ( $this->formType === 'AttackerCard' ) {
            return $game->getBattle()->getAttackerCard()->getBattleEvent()->getFormType($game);
        } elseif ( $this->formType === 'DefenderCard' ) {
            return $game->getBattle()->getDefenderCard()->getBattleEvent()->getFormType($game);
        } else {
            return $this->formType;
        }
    }

    /**
     * @param Game $game
     * @return bool
     */
    public function hasDecision(Game $game)
    {
        return !is_null($this->getFormType($game));
    }

    /**
     * Set nextActionDetails
     *
     * @param \Meldon\WotRBundle\Entity\ActionDetails $nextActionDetails
     * @return SubAction
     */
    public function setNextActionDetails(\Meldon\WotRBundle\Entity\ActionDetails $nextActionDetails = null)
    {
        $this->nextActionDetails = $nextActionDetails;

        return $this;
    }

    /**
     * Get nextActionDetails
     *
     * @return \Meldon\WotRBundle\Entity\ActionDetails 
     */
    public function getNextActionDetails()
    {
        return $this->nextActionDetails;
    }

    /**
     * Set defaultSide
     *
     * @param string $defaultSide
     * @return SubAction
     */
    public function setDefaultSide($defaultSide)
    {
        $this->defaultSide = $defaultSide;

        return $this;
    }

    /**
     * Get defaultSide
     *
     * @return string 
     */
    public function getDefaultSide()
    {
        return $this->defaultSide;
    }
}
