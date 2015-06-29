<?php
namespace GamesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Game
 *
 * @ORM\Table(name="symtest.game")
 * @ORM\Entity
 */
class Game
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \GamesBundle\Entity\Boardgame
     *
     * @ORM\ManyToOne(targetEntity="Boardgame", inversedBy="games")
     * @ORM\JoinColumn(name="boardgame_id", referencedColumnName="id")
     */
    private $boardgame;

    /**
     * @var integer
     *
     * @ORM\Column(name="numplayers", type="integer")
     */
    private $numplayers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="game")
     * @var [Player] 
     */
    private $players;

    
    public function __construct()
    {
        $this->players = new ArrayCollection;
    }

    public function __toString() {
        return $this->name;
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
     * @return Game
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
     * Set numplayers
     *
     * @param integer $numplayers
     * @return Game
     */
    public function setNumplayers($numplayers)
    {
        $this->numplayers = $numplayers;

        return $this;
    }

    /**
     * Get numplayers
     *
     * @return integer 
     */
    public function getNumplayers()
    {
        return $this->numplayers;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Game
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set boardgame
     *
     * @param \GamesBundle\Entity\Boardgame $boardgame
     * @return Game
     */
    public function setBoardgame(\GamesBundle\Entity\Boardgame $boardgame = null)
    {
        $this->boardgame = $boardgame;

        return $this;
    }

    /**
     * Get boardgame
     *
     * @return \GamesBundle\Entity\Boardgame 
     */
    public function getBoardgame()
    {
        return $this->boardgame;
    }

    /**
     * Add players
     *
     * @param \GamesBundle\Entity\Player $players
     * @return Game
     */
    public function addPlayer(\GamesBundle\Entity\Player $players)
    {
        $this->players[] = $players;

        return $this;
    }

    /**
     * Remove players
     *
     * @param \GamesBundle\Entity\Player $players
     */
    public function removePlayer(\GamesBundle\Entity\Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }
}
