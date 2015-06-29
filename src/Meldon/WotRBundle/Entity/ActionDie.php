<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ActionDie
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.actiondie")
 */
class ActionDie {
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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="actionDice")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    /**
     * @ORM\ManyToOne(targetEntity="ActionDieDetails", fetch="EAGER")
     */
    private $details;
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $used = false;
    /**
     *
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $usedForFS = false;
    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="actionDie", cascade={"remove"})
     */
    private $action;
    /**
     * Details and game are optional
     * @param \Meldon\WotRBundle\Entity\ActionDieDetails $details
     * @param \Meldon\WotRBundle\Entity\Game $game
     */
    public function __construct(ActionDieDetails $details = NULL, Game $game = NULL)    {
        if ( $details )
        {
            $this->setDetails($details);
        }
        if ( $game )
        {
            $this->setGame($game);
        }
    }
    public function __toString() {
        return $this->getName();
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
     * Set used
     *
     * @param boolean $used
     * @return ActionDie
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean 
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return ActionDie
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->addActionDice($this);
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
    
    public function removeGame()
    {
        $this->getGame()->getActionDice()->removeElement($this);
        $this->game = NULL;
    }

    /**
     * Set details
     *
     * @param \Meldon\WotRBundle\Entity\ActionDieDetails $details
     * @return ActionDie
     */
    public function setDetails(\Meldon\WotRBundle\Entity\ActionDieDetails $details = null)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return \Meldon\WotRBundle\Entity\ActionDieDetails 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set usedForFS
     *
     * @param boolean $usedForFS
     * @return ActionDie
     */
    public function setUsedForFS($usedForFS)
    {
        $this->usedForFS = $usedForFS;

        return $this;
    }

    /**
     * Get usedForFS
     *
     * @return boolean 
     */
    public function getUsedForFS()
    {
        return $this->usedForFS;
    }

    public function markUsed()
    {
        $this->setUsed(true);
    }

    /**
     * Add action
     *
     * @param \Meldon\WotRBundle\Entity\Action $action
     * @return ActionDie
     */
    public function addAction(\Meldon\WotRBundle\Entity\Action $action)
    {
        $this->action[] = $action;

        return $this;
    }

    /**
     * Remove action
     *
     * @param \Meldon\WotRBundle\Entity\Action $action
     */
    public function removeAction(\Meldon\WotRBundle\Entity\Action $action)
    {
        $this->action->removeElement($action);
    }

    /**
     * Get action
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAction()
    {
        return $this->action;
    }

    public function getImage()
    {
        if ( $this->getUsedForFS() ) {
            return $this->getDetails()->getUsedImage();
        } else {
            return $this->getDetails()->getImage();
        }
    }
}
