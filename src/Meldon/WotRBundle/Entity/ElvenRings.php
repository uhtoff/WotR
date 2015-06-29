<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ElvenRings
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.elvenrings")
 */
class ElvenRings {
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
    private $numAvail;
    /**
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     * @ORM\Column(type="integer")
     */
    private $lastUsed = 0;
    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="rings")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $game;
    public function __construct( Side $side, $number, Game $game )
    {
        $this->setSide($side);
        $this->setNumAvail($number);
        $this->setGame($game);
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
     * Set numAvail
     *
     * @param integer $numAvail
     * @return ElvenRings
     */
    public function setNumAvail($numAvail)
    {
        $this->numAvail = $numAvail;

        return $this;
    }

    /**
     * Get numAvail
     *
     * @return integer 
     */
    public function getNumAvail()
    {
        return $this->numAvail;
    }

    /**
     * Set lastUsed
     *
     * @param integer $lastUsed
     * @return ElvenRings
     */
    public function setLastUsed($lastUsed)
    {
        $this->lastUsed = $lastUsed;

        return $this;
    }

    /**
     * Get lastUsed
     *
     * @return integer 
     */
    public function getLastUsed()
    {
        return $this->lastUsed;
    }

    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return ElvenRings
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
     * Set game
     *
     * @param \Meldon\WotRBundle\Entity\Game $game
     * @return ElvenRings
     */
    public function setGame(\Meldon\WotRBundle\Entity\Game $game = null)
    {
        $this->game = $game;
        $game->addRing($this);
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

}
