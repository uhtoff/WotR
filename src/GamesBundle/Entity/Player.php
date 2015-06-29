<?php
namespace GamesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use GamesBundle\Entity\Game;

/**
 * @ORM\Table(name="symtest.player")
 * @ORM\Entity()
 *
 * @author Russell
 */
class Player 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @var User
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="players")
     * @var Game
     */
    private $game;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer", name="side_id")
     */
    private $side;

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
     * Set side
     *
     * @param integer $side
     * @return Player
     */
    public function setSide($side)
    {
        $this->side = $side;

        return $this;
    }

    /**
     * Get side
     *
     * @return integer 
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * Set game
     *
     * @param \GamesBundle\Entity\Game $game
     * @return Player
     */
    public function setGame(\GamesBundle\Entity\Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \GamesBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Player
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
