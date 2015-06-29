<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 19/05/2015
 * Time: 22:18
 */

namespace Meldon\WotRBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Meldon\WotRBundle\Entity\Game;
use Meldon\WotRBundle\Entity\LogItem;

/**
 * @ORM\Entity
 * @ORM\Table(name="log")
 */
 
class Log  {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Game", inversedBy="log")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    /**
     * @ORM\OneToMany(targetEntity="LogItem", mappedBy="log", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $logItems;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logItems = new ArrayCollection();
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
     * @param Game $game
     * @return Log
     */
    public function setGame(Game $game = null)
    {
        $this->game = $game;
        $game->setLog($this);
        return $this;
    }

    /**
     * Get game
     *
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Add logItems
     *
     * @param LogItem $logItems
     * @return Log
     */
    public function addLogItem(LogItem $logItems)
    {
        $this->logItems[] = $logItems;

        return $this;
    }

    /**
     * Remove logItems
     *
     * @param LogItem $logItems
     */
    public function removeLogItem(LogItem $logItems)
    {
        $this->logItems->removeElement($logItems);
    }

    /**
     * Get logItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLogItems()
    {
        return $this->logItems;
    }
    public function display()
    {
        /** @var $lI LogItem */
        foreach( $this->getLogItems() as $lI )
        {
            $lI->display();
        }
    }
}
