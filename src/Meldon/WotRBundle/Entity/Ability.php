<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Ability
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.ability")
 */
class Ability {

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
     * @ORM\Column(length=100)
     */
    private $name;
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="text")
     */
    private $text;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $guide;


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
     * @return Ability
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
     * Set text
     *
     * @param string $text
     * @return Ability
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set guide
     *
     * @param boolean $guide
     * @return Ability
     */
    public function setGuide($guide)
    {
        $this->guide = $guide;

        return $this;
    }

    /**
     * Get guide
     *
     * @return boolean 
     */
    public function getGuide()
    {
        return $this->guide;
    }
}
