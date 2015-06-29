<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of UnitEntryDetails
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="UnitEntryDetailsRepository")
 * @ORM\Table(name="wotrnew.unitentrydetails")
 */
class UnitEntryDetails {
    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToMany(targetEntity="Scenario")
     */
    private $scenario;
    /**
     * @ORM\ManyToOne(targetEntity="UnitType")
     */
    private $unitType;
    /**
     * @ORM\ManyToOne(targetEntity="NationDetails")
     */
    private $nationDetails;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $number;
    /**
     * @ORM\ManyToOne(targetEntity="LocationDetails")
     */
    private $startLoc;
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
     * Set number
     *
     * @param integer $number
     * @return UnitEntryDetails
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
     * Add scenario
     *
     * @param \Meldon\WotRBundle\Entity\Scenario $scenario
     * @return UnitEntryDetails
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

    /**
     * Set unitType
     *
     * @param \Meldon\WotRBundle\Entity\UnitType $unitType
     * @return UnitEntryDetails
     */
    public function setUnitType(\Meldon\WotRBundle\Entity\UnitType $unitType = null)
    {
        $this->unitType = $unitType;

        return $this;
    }

    /**
     * Get unitType
     *
     * @return \Meldon\WotRBundle\Entity\UnitType 
     */
    public function getUnitType()
    {
        return $this->unitType;
    }

    /**
     * Set nationDetails
     *
     * @param \Meldon\WotRBundle\Entity\NationDetails $nationDetails
     * @return UnitEntryDetails
     */
    public function setNationDetails(\Meldon\WotRBundle\Entity\NationDetails $nationDetails = null)
    {
        $this->nationDetails = $nationDetails;

        return $this;
    }

    /**
     * Get nationDetails
     *
     * @return \Meldon\WotRBundle\Entity\NationDetails 
     */
    public function getNationDetails()
    {
        return $this->nationDetails;
    }

    /**
     * Set startLoc
     *
     * @param \Meldon\WotRBundle\Entity\LocationDetails $startLoc
     * @return UnitEntryDetails
     */
    public function setStartLoc(\Meldon\WotRBundle\Entity\LocationDetails $startLoc = null)
    {
        $this->startLoc = $startLoc;

        return $this;
    }

    /**
     * Get startLoc
     *
     * @return \Meldon\WotRBundle\Entity\LocationDetails 
     */
    public function getStartLoc()
    {
        return $this->startLoc;
    }
}
