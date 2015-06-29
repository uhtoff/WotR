<?php
namespace Meldon\WotRBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of BattleEvent
 *
 * @author Russell
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.battleevent")
 */
class BattleEvent {
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
     * @ORM\Column(length=250)
     */
    private $name;
    /**
     * @ORM\Column(type="text")
     */
    private $battleText;
    /**
     * @ORM\Column(type="text", nullable = true)
     */
    private $prereqText;
    /**
     * @ORM\Column(type="integer")
     */
    private $initiative;
    /**
     * @ORM\Column(length=50, nullable = true)
     */
    private $formType;
    /**
     * @ORM\Column(length=50, nullable = true)
     */
    private $actionMethod;
    /**
     * @ORM\ManyToOne(targetEntity="ActionDetails")
     */
    private $nextActionDetails;
    /**
     * @ORM\Column(length=10, nullable=true)
     */
    private $defaultSide;

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
     * @return BattleEvent
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
     * Set battleText
     *
     * @param string $battleText
     * @return BattleEvent
     */
    public function setBattleText($battleText)
    {
        $this->battleText = $battleText;

        return $this;
    }

    /**
     * Get battleText
     *
     * @return string 
     */
    public function getBattleText()
    {
        return $this->battleText;
    }

    /**
     * Set prereqText
     *
     * @param string $prereqText
     * @return BattleEvent
     */
    public function setPrereqText($prereqText)
    {
        $this->prereqText = $prereqText;

        return $this;
    }

    /**
     * Get prereqText
     *
     * @return string 
     */
    public function getPrereqText()
    {
        return $this->prereqText;
    }

    /**
     * Set initiative
     *
     * @param integer $initiative
     * @return BattleEvent
     */
    public function setInitiative($initiative)
    {
        $this->initiative = $initiative;

        return $this;
    }

    /**
     * Get initiative
     *
     * @return integer 
     */
    public function getInitiative()
    {
        return $this->initiative;
    }

    /**
     * Set formType
     *
     * @param string $formType
     * @return BattleEvent
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;

        return $this;
    }

    /**
     * Get formType
     *
     * @return string 
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * Set actionMethod
     *
     * @param string $actionMethod
     * @return BattleEvent
     */
    public function setActionMethod($actionMethod)
    {
        $this->actionMethod = $actionMethod;

        return $this;
    }

    /**
     * Get actionMethod
     *
     * @return string 
     */
    public function getActionMethod()
    {
        return $this->actionMethod;
    }

    /**
     * Set defaultSide
     *
     * @param string $defaultSide
     * @return BattleEvent
     */
    public function setDefaultSide($defaultSide)
    {
        $this->defaultSide = $defaultSide;

        return $this;
    }

    /**
     * Get defaultSide
     *
     * @return string 
     */
    public function getDefaultSide()
    {
        return $this->defaultSide;
    }

    /**
     * Set nextActionDetails
     *
     * @param \Meldon\WotRBundle\Entity\ActionDetails $nextActionDetails
     * @return BattleEvent
     */
    public function setNextActionDetails(\Meldon\WotRBundle\Entity\ActionDetails $nextActionDetails = null)
    {
        $this->nextActionDetails = $nextActionDetails;

        return $this;
    }

    /**
     * Get nextActionDetails
     *
     * @return \Meldon\WotRBundle\Entity\ActionDetails 
     */
    public function getNextActionDetails()
    {
        return $this->nextActionDetails;
    }
}
