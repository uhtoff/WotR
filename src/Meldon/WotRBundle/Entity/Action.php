<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Null;

/**
 * Description of Action
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="action")
 */
class Action {
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
     * @var ActionDetails
     * @ORM\ManyToOne(targetEntity="ActionDetails")
     */
    private $details;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $currentSubActionIndex = 0;
    /**
     * @ORM\ManyToOne(targetEntity="ActionDie", inversedBy="action")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $actionDie;
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $data;
    /**
     * @var Side
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     * @ORM\ManyToOne(targetEntity="ActionStack", inversedBy="actions")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $stack;
    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    public function __construct(ActionDetails $aD, Side $side = NULL)
    {
        $this->setDetails($aD);
        $this->setSide($side);
        $this->setData(array());
    }

    public function setNextAction(Game $game)
    {
        $nA = new Action($this->getNextAction());
        $game->setCurrentAction($nA);
    }
    /**
     * Return currently set subAction
     * @return SubAction
     */
    public function getSubAction()
    {
        $sAO = $this->getSubActionOrder()->get($this->getCurrentSubActionIndex());
        if ( $sAO ) {
            return $sAO->getSubAction();
        } else {
            return false;
        }
    }
    /**
     * Check to see if the next subAction exists, if it does return it, if not return false
     * @return boolean
     */
    public function hasNextSubAction()
    {
        $nextIndex = $this->getCurrentSubActionIndex() + 1;
        if ( $this->getSubActionOrder()->get($nextIndex)){
            return true;
        } else {
            return false;
        }

    }

    /**
     * If there is another subAction to execute then do so, otherwise clear the action from the stack as it's done with
     */
    public function setNextSubAction($game) {
        if ($this->hasNextSubAction()) {
            $this->setCurrentSubActionIndex($this->getCurrentSubActionIndex() + 1);
            if ( !$this->getSubAction()->isAvailable($game) ) {
                $this->setNextSubAction($game);
            }
        } else {
            $this->getStack()->removeAction($this);
        }
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

    public function hasDecision(Game $game) {
        $sA = $this->getSubAction();
        if ( $sA && $sA->isAvailable($game) && $sA->hasDecision($game) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Game $game
     * @param $data
     * @throws EventMethodNotDefinedException
     */
    public function execute(Game $game, $data){
        /** @var  $sA SubAction */
        $sA = $this->getSubAction();
        $sA->execute($game, $data);
        $this->setNextSubAction($game);
        $this->setSide(NULL);
    }

    public function getFormType($game){
        $sA = $this->getSubAction();
        return $sA ? $sA->getFormType($game) : false;
    }

    /**
     * @return bool|string
     */
    public function getActionMethod(Game $game) {
        $sA = $this->getSubAction();
        return $sA ? $sA->getActionMethod($game) : false;
    }

    public function getDefaultSide() {
        $sA = $this->getSubAction();
        return $sA ? $sA->getDefaultSide() : false;
    }

    public function getNextAction() {
        $sA = $this->getSubAction();
        return $sA ? $sA->getNextActionDetails() : false;
    }


    /**
     * Set details
     *
     * @param \Meldon\WotRBundle\Entity\ActionDetails $details
     * @return Action
     */
    public function setDetails(\Meldon\WotRBundle\Entity\ActionDetails $details = null)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return \Meldon\WotRBundle\Entity\ActionDetails 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set currentSubActionIndex
     *
     * @param integer $currentSubActionIndex
     * @return Action
     */
    public function setCurrentSubActionIndex($currentSubActionIndex)
    {
        $this->currentSubActionIndex = $currentSubActionIndex;

        return $this;
    }

    /**
     * Get currentSubActionIndex
     *
     * @return integer 
     */
    public function getCurrentSubActionIndex()
    {
        return $this->currentSubActionIndex;
    }

    /**
     * Set actionDie
     *
     * @param \Meldon\WotRBundle\Entity\ActionDie $actionDie
     * @return Action
     */
    public function setActionDie(\Meldon\WotRBundle\Entity\ActionDie $actionDie = null)
    {
        $this->actionDie = $actionDie;

        return $this;
    }

    /**
     * Get actionDie
     *
     * @return \Meldon\WotRBundle\Entity\ActionDie 
     */
    public function getActionDie()
    {
        return $this->actionDie;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return Action
     */
    private function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function addData($data) {
        $this->data = array_merge_recursive($this->data, $data);

        return $this;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Action
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return Action
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
     * Set stack
     *
     * @param \Meldon\WotRBundle\Entity\ActionStack $stack
     * @return Action
     */
    public function setStack(\Meldon\WotRBundle\Entity\ActionStack $stack = null)
    {
        $this->stack = $stack;
        $stack->addAction($this);
        return $this;
    }

    /**
     * Get stack
     *
     * @return \Meldon\WotRBundle\Entity\ActionStack 
     */
    public function getStack()
    {
        return $this->stack;
    }

    public function unsetStack() {
        $this->stack = NULL;
    }

    /**
     * @return bool
     */
    public function hasDecisionSet($game)
    {
        return ( $this->getSide() && $this->hasDecision($game) );
    }

    public function decreaseIndex() {
        $this->setCurrentSubActionIndex($this->getCurrentSubActionIndex()-1);
    }
}
