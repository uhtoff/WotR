<?php
namespace Meldon\WotRBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of CardGroupDetails
 *
 * @author Russell
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.cardgroupdetails")
 */
class CardGroupDetails {
    const HIDDEN = 1;
    const VISIBLE = 2;
    const SIDEONLY = 3;
    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="GroupType")
     */
    private $groupType;
    /**
     * @ORM\Column(length=20)
     */
    private $groupContents;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     *
     * @ORM\Column(length=250,nullable=true)
     */
    private $name;
    /**
     *
     * @ORM\OneToMany(targetEntity="CardMapping", mappedBy="source")
     */
    private $mappings;
    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $visibility;
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
     * @return CardGroupDetails
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
     * Set groupType
     *
     * @param \Meldon\WotRBundle\Entity\GroupType $groupType
     * @return CardGroupDetails
     */
    public function setGroupType(\Meldon\WotRBundle\Entity\GroupType $groupType = null)
    {
        $this->groupType = $groupType;

        return $this;
    }

    /**
     * Get groupType
     *
     * @return \Meldon\WotRBundle\Entity\GroupType 
     */
    public function getGroupType()
    {
        return $this->groupType;
    }
    
    public function getGroupTypeName()
    {
        return $this->getGroupType()->getName();
    }
    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return CardGroupDetails
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
     * Constructor
     */
    public function __construct()
    {
        $this->mappings = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add mappings
     *
     * @param \Meldon\WotRBundle\Entity\CardMapping $mappings
     * @return CardGroupDetails
     */
    public function addMapping(\Meldon\WotRBundle\Entity\CardMapping $mappings)
    {
        $this->mappings[] = $mappings;

        return $this;
    }

    /**
     * Remove mappings
     *
     * @param \Meldon\WotRBundle\Entity\CardMapping $mappings
     */
    public function removeMapping(\Meldon\WotRBundle\Entity\CardMapping $mappings)
    {
        $this->mappings->removeElement($mappings);
    }

    /**
     * Get mappings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMappings()
    {
        return $this->mappings;
    }

    /**
     * Set visibility
     *
     * @param integer $visibility
     * @return CardGroupDetails
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return integer 
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param Side $side
     * @return bool
     */
    public function isVisible(Side $side){
        if ( $this->getVisibility() === self::VISIBLE ||
            ( $this->getVisibility() === self::SIDEONLY && $this->getSide() === $side )) {
            return true;
        } else {
            return false;
        }

    }

    public function isEvent() {
        if ( $this->getGroupContents() == 'event' ) {
            return true;
        } else {
            return false;
        }
    }

    public function isHunt() {
        if ( $this->getGroupContents() == 'hunt' ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set groupContents
     *
     * @param string $groupContents
     * @return CardGroupDetails
     */
    public function setGroupContents($groupContents)
    {
        $this->groupContents = $groupContents;

        return $this;
    }

    /**
     * Get groupContents
     *
     * @return string 
     */
    public function getGroupContents()
    {
        return $this->groupContents;
    }
}
