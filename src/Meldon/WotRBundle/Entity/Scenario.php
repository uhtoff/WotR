<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Scenario
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.scenario")
 */
class Scenario {

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
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $FPRings;
    /**
     *
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $SRings;
    /**
     *
     * @var LocationDetails
     * @ORM\ManyToOne(targetEntity="LocationDetails")
     */
    private $FSStart;
    /**
     * @var CharacterDetails
     * @ORM\ManyToOne(targetEntity="CharacterDetails")
     */
    private $startGuide;
    /**
     *
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $startFPDice;
    /**
     *
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $startSDice;
    /**
     *
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $corruption;

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
     * @return Scenario
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
     * Set FPRings
     *
     * @param integer $fPRings
     * @return Scenario
     */
    public function setFPRings($fPRings)
    {
        $this->FPRings = $fPRings;

        return $this;
    }

    /**
     * Get FPRings
     *
     * @return integer 
     */
    public function getFPRings()
    {
        return $this->FPRings;
    }

    /**
     * Set SRings
     *
     * @param integer $sRings
     * @return Scenario
     */
    public function setSRings($sRings)
    {
        $this->SRings = $sRings;

        return $this;
    }

    /**
     * Get SRings
     *
     * @return integer 
     */
    public function getSRings()
    {
        return $this->SRings;
    }

    /**
     * Set corruption
     *
     * @param integer $corruption
     * @return Scenario
     */
    public function setCorruption($corruption)
    {
        $this->corruption = $corruption;

        return $this;
    }

    /**
     * Get corruption
     *
     * @return integer 
     */
    public function getCorruption()
    {
        return $this->corruption;
    }

    /**
     * Set FSStart
     *
     * @param \Meldon\WotRBundle\Entity\LocationDetails $fSStart
     * @return Scenario
     */
    public function setFSStart(\Meldon\WotRBundle\Entity\LocationDetails $fSStart = null)
    {
        $this->FSStart = $fSStart;

        return $this;
    }

    /**
     * Get FSStart
     *
     * @return \Meldon\WotRBundle\Entity\LocationDetails 
     */
    public function getFSStart()
    {
        return $this->FSStart;
    }

    /**
     * Set startFPDice
     *
     * @param integer $startFPDice
     * @return Scenario
     */
    public function setStartFPDice($startFPDice)
    {
        $this->startFPDice = $startFPDice;

        return $this;
    }

    /**
     * Get startFPDice
     *
     * @return integer 
     */
    public function getStartFPDice()
    {
        return $this->startFPDice;
    }

    /**
     * Set startSDice
     *
     * @param integer $startSDice
     * @return Scenario
     */
    public function setStartSDice($startSDice)
    {
        $this->startSDice = $startSDice;

        return $this;
    }

    /**
     * Get startSDice
     *
     * @return integer 
     */
    public function getStartSDice()
    {
        return $this->startSDice;
    }

    /**
     * Set startGuide
     *
     * @param \Meldon\WotRBundle\Entity\CharacterDetails $startGuide
     * @return Scenario
     */
    public function setStartGuide(\Meldon\WotRBundle\Entity\CharacterDetails $startGuide = null)
    {
        $this->startGuide = $startGuide;

        return $this;
    }

    /**
     * Get startGuide
     *
     * @return \Meldon\WotRBundle\Entity\CharacterDetails 
     */
    public function getStartGuide()
    {
        return $this->startGuide;
    }
    
    public function getStartRings( Side $side )
    {
        return $this->{'get'.$side->getAbbreviation().'Rings'}();
    }
    
    public function getStartDice( Side $side )
    {
        return $this->{'getStart'.$side->getAbbreviation().'Dice'}();
    }
}
