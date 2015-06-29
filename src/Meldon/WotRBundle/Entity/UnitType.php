<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of UnitType
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.unittype")
 */
class UnitType {

    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(length=50)
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     */
    private $strength;
    /**
     * @ORM\Column(type="integer")
     */
    private $leadership;
    /**
     * @ORM\Column(type="integer")
     */
    private $hits;

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
     * @return UnitType
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
     * Set strength
     *
     * @param integer $strength
     * @return UnitType
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength
     *
     * @return integer 
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Set leadership
     *
     * @param integer $leadership
     * @return UnitType
     */
    public function setLeadership($leadership)
    {
        $this->leadership = $leadership;

        return $this;
    }

    /**
     * Get leadership
     *
     * @return integer 
     */
    public function getLeadership()
    {
        return $this->leadership;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     * @return UnitType
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer 
     */
    public function getHits()
    {
        return $this->hits;
    }
}
