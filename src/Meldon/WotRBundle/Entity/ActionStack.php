<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 31/05/2015
 * Time: 12:30
 */

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of ActionStack
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.actionstack")
 */
class ActionStack {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Game", inversedBy="actionStack")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="stack", cascade={"persist","remove"}, orphanRemoval=true, indexBy="position")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $actions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ActionStack
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->setActionStack($this);
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
     * Add actions
     *
     * @param \Meldon\WotRBundle\Entity\Action $actions
     * @return ActionStack
     */
    public function addAction(\Meldon\WotRBundle\Entity\Action $actions)
    {
        $this->actions[] = $actions;

        return $this;
    }

    /**
     * Remove actions
     *
     * @param \Meldon\WotRBundle\Entity\Action $actions
     */
    public function removeAction(\Meldon\WotRBundle\Entity\Action $actions)
    {
        $this->actions->removeElement($actions);
        $actions->unsetStack();
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActions()
    {
        return $this->actions;
    }
    /**
     * Return number of decisions on the stack
     * @return integer
     */
    public function numOnStack()
    {
        return count( $this->getActions() );
    }

    /**
     * Map through array collection getting positions of all decisions
     * @return integer[]
     */
    public function getPositions()
    {
        return $this->getActions()
            ->map(function($d){return $d->getPosition();})->toArray();
    }
    /**
     * @return Action
     */
    public function getCurrentAction()
    {
        if ( $this->getActions()->count() > 0 ) {
            $topPos = min($this->getPositions());
            return $this->getActions()->filter(
                function ($e) use ($topPos) {
                    return $e->getPosition() === $topPos;
                }
            )->first();
        } else {
            return null;
        }

    }
    /**
     * Returns the top decision from the array
     * @return Action|boolean
     */
    public function takeFromTop()
    {
        $d = $this->getCurrentAction();
        if ( $d !== false )
        {
            $this->removeAction($d);
            return $d;
        } else
        {
            return false;
        }
    }
    /**
     * Returns the bottom decision from the array
     * @return Action|boolean
     */
    public function takeFromBottom()
    {
        $d = $this->getActions()->last();
        if ( $d !== false )
        {
            $this->removeAction($d);
            return $d;
        } else {
            return false;
        }
    }

    /**
     * Adds a decision to the top of the stack, shuffles down the positions if necessary
     * @param Action $d
     * @return Action $d
     */
    public function addToTop(Action $d)
    {
        $pos = $this->getPositions();
        // If card group is empty
        if ( count($pos) != 0 ) {
            $topPos = min($this->getPositions());
            if ( $topPos == 1 ) {
                $this->getActions()
                    ->map(function($d){$d->setPosition($d->getPosition() + 1);});
            }
        }
        $d->setPosition(1);
        $d->setStack($this);
        return $d;
    }

    /**
     * Adds a decision to the bottom of the stack
     * @param Action $d
     * @return Action
     */
    public function addToBottom(Action $d) {
        $pos = $this->getPositions();
        if ( count($pos) == 0 )
        {
            $bottomPos = 0;
        } else
        {
            $bottomPos = max($pos);
        }
        $d->setPosition($bottomPos + 1);
        $d->setStack($this);
        return $d;
    }

    /**
     * Sort stack by position order
     */
    protected function sortStack() {
        $iterator = $this->getActions()->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });
        $array = iterator_to_array($iterator);
        $this->actions = new ArrayCollection(iterator_to_array($iterator));
    }

    public function removeActionByID($a)
    {
        foreach( $this->getActions() as $action ) {
            if ( $action->getID() === $a->getID() ) {
                $this->removeAction($action);
                break;
            }
        }
        $a->unsetStack();
    }
}
