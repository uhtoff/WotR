<?php
namespace GamesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Game
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="symtest.boardgame")
 */
class Boardgame 
{
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    protected $minplayers;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    protected $maxplayers;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    protected $scenarios;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    protected $privilege;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    protected $active;
    /**
     *
     * @var [GamesBundle\Entity\Game]
     * 
     * @ORM\OneToMany(targetEntity="Game", mappedBy="boardgame")
     */
    protected $games;
    
    public function __construct()
    {
        $this->games = new ArrayCollection();
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
     * @return Boardgame
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
     * Set minplayers
     *
     * @param integer $minplayers
     * @return Boardgame
     */
    public function setMinplayers($minplayers)
    {
        $this->minplayers = $minplayers;

        return $this;
    }

    /**
     * Get minplayers
     *
     * @return integer 
     */
    public function getMinplayers()
    {
        return $this->minplayers;
    }

    /**
     * Set maxplayers
     *
     * @param integer $maxplayers
     * @return Boardgame
     */
    public function setMaxplayers($maxplayers)
    {
        $this->maxplayers = $maxplayers;

        return $this;
    }

    /**
     * Get maxplayers
     *
     * @return integer 
     */
    public function getMaxplayers()
    {
        return $this->maxplayers;
    }

    /**
     * Set scenarios
     *
     * @param integer $scenarios
     * @return Boardgame
     */
    public function setScenarios($scenarios)
    {
        $this->scenarios = $scenarios;

        return $this;
    }

    /**
     * Get scenarios
     *
     * @return integer 
     */
    public function getScenarios()
    {
        return $this->scenarios;
    }

    /**
     * Set privilege
     *
     * @param integer $privilege
     * @return Boardgame
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;

        return $this;
    }

    /**
     * Get privilege
     *
     * @return integer 
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Boardgame
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
     * Add games
     *
     * @param \GamesBundle\Entity\Game $games
     * @return Boardgame
     */
    public function addGame(\GamesBundle\Entity\Game $games)
    {
        $this->games[] = $games;

        return $this;
    }

    /**
     * Remove games
     *
     * @param \GamesBundle\Entity\Game $games
     */
    public function removeGame(\GamesBundle\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get games
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }
}
