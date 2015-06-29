<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Side
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="SideRepository")
 * @ORM\Table(name="wotrnew.side")
 */
class Side {
    const FP = 1;
    const S = 2;
    const N = 3;
    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     *
     * @var string
     * 
     * @ORM\Column(length=50)
     */
    private $name;
    /**
     *
     * @var string
     * 
     * @ORM\Column(length=10)
     */
    private $abbreviation;
    /**
     *
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $played;
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
     * @return Side
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
     * Set abbreviation
     *
     * @param string $abbreviation
     * @return Side
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string 
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }


    /**
     * Set played
     *
     * @param boolean $played
     * @return Side
     */
    public function setPlayed($played)
    {
        $this->played = $played;

        return $this;
    }

    /**
     * Get played
     *
     * @return boolean 
     */
    public function getPlayed()
    {
        return $this->played;
    }

    /**
     * Return true if is the Free Peoples
     * @return bool
     */
    public function isFP()
    {
        return $this->getId() == self::FP;
    }


    /**
     * Return true if is the Shadow
     * @return bool
     */
    public function isShadow()
    {
        return $this->getId() == self::S;
    }
}
