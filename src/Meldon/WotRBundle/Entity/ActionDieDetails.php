<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Meldon\WotRBundle\Entity\Side;

/**
 * Description of ActionDieDetails
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="ActionDieDetailsRepository")
 * @ORM\Table(name="wotrnew.actiondiedetails")
 */
class ActionDieDetails {
    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(length=100)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(length=100)
     */
    private $image;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $number;
    /**
     * @var Side
     * @ORM\ManyToOne(targetEntity="side")
     */
    private $side;
    /**
     * @var string
     * @ORM\Column(length=100, nullable=true)
     */
    private $usedImage;
    /**
     * @ORM\ManyToMany(targetEntity="ActionDetails", mappedBy="actionDieDetails")
     */
    private $actions;

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
     * @return ActionDieDetails
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
     * Set image
     *
     * @param string $image
     * @return ActionDieDetails
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return ActionDieDetails
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\side $side
     * @return ActionDieDetails
     */
    public function setSide(\Meldon\WotRBundle\Entity\side $side = null)
    {
        $this->side = $side;

        return $this;
    }

    /**
     * Get side
     *
     * @return \Meldon\WotRBundle\Entity\side 
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * Set usedImage
     *
     * @param string $usedImage
     * @return ActionDieDetails
     */
    public function setUsedImage($usedImage)
    {
        $this->usedImage = $usedImage;

        return $this;
    }

    /**
     * Get usedImage
     *
     * @return string 
     */
    public function getUsedImage()
    {
        return $this->usedImage;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add actions
     *
     * @param \Meldon\WotRBundle\Entity\Action $actions
     * @return ActionDieDetails
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
}
