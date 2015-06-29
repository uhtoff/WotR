<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Character
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="CharacterDetailsRepository")
 * @ORM\Table(name="wotrnew.characterdetails")
 */
class CharacterDetails {
    const CAPTAIN = 8;
    /**
     *
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
     * @ORM\Column(length=250)
     */
    private $name;
    /**
     *
     * @var string
     * 
     * @ORM\Column(length=50)
     */
    private $subtitle;
    /**
     * @var string
     * 
     * @ORM\Column(type="text")
     */
    private $entry;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    private $level;
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    private $leadership;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $dice;
    /**
     * @ORM\ManyToMany(targetEntity="NationDetails")
     */
    private $nationDetails;
    /**
     *
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="LocationDetails")
     */
    private $startloc;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $nazgul;
    /**
     *
     * @var type 
     * 
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean")
     */
    private $startInFellowship;
    /**
     * @var Ability
     * 
     * @ORM\ManyToMany(targetEntity="Ability", indexBy="id")
     */
    private $abilities;
    /**
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
     * @return BaseCharacter
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
     * Set subtitle
     *
     * @param string $subtitle
     * @return BaseCharacter
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set cardText
     *
     * @param string $cardText
     * @return BaseCharacter
     */
    public function setCardText($cardText)
    {
        $this->cardText = $cardText;

        return $this;
    }

    /**
     * Get cardText
     *
     * @return string 
     */
    public function getCardText()
    {
        return $this->cardText;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return BaseCharacter
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set leadership
     *
     * @param integer $leadership
     * @return BaseCharacter
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
     * Set dice
     *
     * @param boolean $dice
     * @return BaseCharacter
     */
    public function setDice($dice)
    {
        $this->dice = $dice;

        return $this;
    }

    /**
     * Get dice
     *
     * @return boolean 
     */
    public function getDice()
    {
        return $this->dice;
    }

    /**
     * Set nazgul
     *
     * @param boolean $nazgul
     * @return BaseCharacter
     */
    public function setNazgul($nazgul)
    {
        $this->nazgul = $nazgul;

        return $this;
    }

    /**
     * Get nazgul
     *
     * @return boolean 
     */
    public function getNazgul()
    {
        return $this->nazgul;
    }

    /**
     * Set nation
     *
     * @param \Meldon\WotRBundle\Entity\NationDetails $nation
     * @return BaseCharacter
     */
    public function setNation(\Meldon\WotRBundle\Entity\NationDetails $nation = null)
    {
        $this->nation = $nation;

        return $this;
    }

    /**
     * Get nation
     *
     * @return \Meldon\WotRBundle\Entity\NationDetails 
     */
    public function getNation()
    {
        return $this->nation;
    }

    /**
     * Set startloc
     *
     * @param \Meldon\WotRBundle\Entity\LocationDetails $startloc
     * @return BaseCharacter
     */
    public function setStartloc(\Meldon\WotRBundle\Entity\LocationDetails $startloc = null)
    {
        $this->startloc = $startloc;

        return $this;
    }

    /**
     * Get startloc
     *
     * @return \Meldon\WotRBundle\Entity\LocationDetails 
     */
    public function getStartloc()
    {
        return $this->startloc;
    }

    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return BaseCharacter
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
     * Add scenario
     *
     * @param \Meldon\WotRBundle\Entity\Scenario $scenario
     * @return BaseCharacter
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
     * Set entry
     *
     * @param string $entry
     * @return BaseCharacter
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return string 
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Add abilities
     *
     * @param \Meldon\WotRBundle\Entity\Ability $abilities
     * @return BaseCharacter
     */
    public function addAbility(\Meldon\WotRBundle\Entity\Ability $abilities)
    {
        $this->abilities[] = $abilities;

        return $this;
    }

    /**
     * Remove abilities
     *
     * @param \Meldon\WotRBundle\Entity\Ability $abilities
     */
    public function removeAbility(\Meldon\WotRBundle\Entity\Ability $abilities)
    {
        $this->abilities->removeElement($abilities);
    }

    /**
     * Get abilities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAbilities()
    {
        return $this->abilities;
    }

    /**
     * Set startInFellowship
     *
     * @param boolean $startInFellowship
     * @return BaseCharacter
     */
    public function setStartInFellowship($startInFellowship)
    {
        $this->startInFellowship = $startInFellowship;

        return $this;
    }

    /**
     * Get startInFellowship
     *
     * @return boolean 
     */
    public function getStartInFellowship()
    {
        return $this->startInFellowship;
    }

    /**
     * Set nationDetails
     *
     * @param \Meldon\WotRBundle\Entity\NationDetails $nationDetails
     * @return CharacterDetails
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
     * Add nationDetails
     *
     * @param \Meldon\WotRBundle\Entity\NationDetails $nationDetails
     * @return CharacterDetails
     */
    public function addNationDetail(\Meldon\WotRBundle\Entity\NationDetails $nationDetails)
    {
        $this->nationDetails[] = $nationDetails;

        return $this;
    }

    /**
     * Remove nationDetails
     *
     * @param \Meldon\WotRBundle\Entity\NationDetails $nationDetails
     */
    public function removeNationDetail(\Meldon\WotRBundle\Entity\NationDetails $nationDetails)
    {
        $this->nationDetails->removeElement($nationDetails);
    }

    public function getStrength()
    {
        if ( $this->getAbilities()->containsKey(SELF::CAPTAIN) ) {
            return 1;
        } else {
            return 0;
        }
    }
}
