<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * @ORM\Table(name="wotrnew.player")
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
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var Game
     */
    private $game;
    /**
     *
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    public function __construct( User $user = NULL, Side $side = NULL, Game $game = NULL )
    {
        if ( $user ) $this->setUser($user);
        if ( $side ) $this->setSide($side);
        if ( $game ) $this->setGame($game);
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

    /**
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return Player
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->addPlayer($this);
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
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return Player
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
    
    public function getName()
    {
        return $this->getUser()->getName();
    }
    public function isShadow()
    {
        return $this->getSide()->getAbbreviation() === 'S';
    }
    public function isFP()
    {
        return $this->getSide()->getAbbreviation() === 'FP';
    }
}
