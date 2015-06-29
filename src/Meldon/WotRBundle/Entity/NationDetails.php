<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Nation
 *
 * @author Russ
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="wotrnew.nationdetails")
 */
class NationDetails {
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
     * @ORM\Column(length=50)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(length=50)
     */
    private $adjective;
    /**
     * @var type
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $startStep;
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $startActive;
    /**
     * @var type
     * @ORM\ManyToMany(targetEntity="Scenario")
     */
    private $scenario;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scenario = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return NationDetails
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
     * Set adjective
     *
     * @param string $adjective
     * @return NationDetails
     */
    public function setAdjective($adjective)
    {
        $this->adjective = $adjective;

        return $this;
    }

    /**
     * Get adjective
     *
     * @return string 
     */
    public function getAdjective()
    {
        return $this->adjective;
    }

    /**
     * Set startActive
     *
     * @param boolean $startActive
     * @return NationDetails
     */
    public function setStartActive($startActive)
    {
        $this->startActive = $startActive;

        return $this;
    }

    /**
     * Get startActive
     *
     * @return boolean 
     */
    public function getStartActive()
    {
        return $this->startActive;
    }

    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return NationDetails
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
     * Set startStep
     *
     * @param integer
     * @return NationDetails
     */
    public function setStartStep($startStep = null)
    {
        $this->startStep = $startStep;
        return $this;
    }

    /**
     * Get startStep
     *
     * @return integer
     */
    public function getStartStep()
    {
        return $this->startStep;
    }

    /**
     * Add scenario
     *
     * @param \Meldon\WotRBundle\Entity\Scenario $scenario
     * @return NationDetails
     */
    public function addScenario(\Meldon\WotRBundle\Entity\Scenario $scenario)
    {
        $this->scenario[] = $scenario;

        return $this;
    }

    /**
     * Remove scenario
     *
     * @param \Meldon\WotRBundle\Entity\Scenario $scenario
     */
    public function removeScenario(\Meldon\WotRBundle\Entity\Scenario $scenario)
    {
        $this->scenario->removeElement($scenario);
    }

    /**
     * Get scenario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getScenario()
    {
        return $this->scenario;
    }
}
