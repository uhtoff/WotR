<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Character
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.characters")
 */
class Character extends Unit {
    use MagicDetails;
    /**
     * @ORM\ManyToOne(targetEntity="Fellowship",inversedBy="characters",cascade={"persist","remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $fellowship;
    /**
     * @ORM\Column(type="boolean")
     */
    private $guide = false;
    /**
     * @ORM\ManyToOne(targetEntity="CharacterDetails", fetch="EAGER")
     */
    private $details;

    public function __construct(Nation $n = NULL, UnitType $uT, Location $l = NULL, Game $g, CharacterDetails $details)
    {
        $this->setDetails($details);
        parent::__construct($n, $uT, $l, $g);
    }

    /**
     * Set fellowship
     *
     * @param \Meldon\WotRBundle\Entity\Fellowship $fellowship
     * @return Character
     */
    public function setFellowship(\Meldon\WotRBundle\Entity\Fellowship $fellowship = null)
    {
        $this->fellowship = $fellowship;
        $fellowship->addCharacter($this);
        return $this;
    }

    /**
     * Get fellowship
     *
     * @return \Meldon\WotRBundle\Entity\Fellowship 
     */
    public function getFellowship()
    {
        return $this->fellowship;
    }

    /**
     * Character leaves Fellowship
     */
    public function leaveFellowship()
    {
        $this->getFellowship()->removeCharacter($this);
        $this->setGuide(false);
        $this->fellowship = NULL;
    }

    /**
     * Set details
     *
     * @param \Meldon\WotRBundle\Entity\CharacterDetails $details
     * @return Character
     */
    public function setDetails(\Meldon\WotRBundle\Entity\CharacterDetails $details = null)
    {
        $this->details = $details;
        $this->setSide($details->getSide());
        return $this;
    }

    /**
     * Get details
     *
     * @return \Meldon\WotRBundle\Entity\CharacterDetails 
     */
    public function getDetails()
    {
        return $this->details;
    }

    public function getName()
    {
        return $this->getDetails()->getName();
    }

    public function getLeadership()
    {
        return $this->getDetails()->getLeadership();
    }

    public function getStrength()
    {
        return $this->getDetails()->getStrength();
    }

    public function isLeader(Game $game)
    {
        return true;
    }

    public function isCharacter()
    {
        return true;
    }

    public function isGuide()
    {
        return $this->guide;
    }

    public function getNameWithGuide()
    {
        $name = $this->getName();
        if ( $this->isGuide() ) {
            $name .= " (Guide)";
        }
        return $name;
    }

    /**
     * Set guide
     *
     * @param boolean $guide
     * @return Character
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

    /**
     * Characters always count as being at War
     * @return true
     */
    public function isNationAtWar()
    {
        return true;
    }

    public function getNameWithLevel()
    {
        return "{$this->getName()} (Level {$this->getLevel()})";
    }

    /**
     * @param bool $casualty
     */
    public function becomeCasualty($casualty = true)
    {
        if ( $this->getFellowship() ) {
            $this->leaveFellowship();
        }
        parent::becomeCasualty($casualty);
        $this->setCasualty(true);
    }

    public function canBeMustered()
    {
        if ( $this->getLocation() == NULL && !$this->getCasualty() ) {
            return true;
        }
    }

    public function getPopover()
    {
        $popover = '<a tabindex="0" data-toggle="popover" data-html="true" title="<b>';
        $popover .= htmlentities($this->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</b><br/><i>';
        $popover .= htmlentities($this->getSubtitle(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</i>" data-content="<p><i>';
        $popover .= htmlentities($this->getEntry(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</i></p>';
        foreach ( $this->getAbilities() as $a ) {
            $popover .= '<p><b>';
            $popover .= htmlentities($a->getName(),ENT_QUOTES|ENT_HTML5);
            $popover .= '.</b> ';
            $popover .= htmlentities($a->getText(),ENT_QUOTES|ENT_HTML5);
            $popover .= '</p>';
        }
        $popover .= '<p>Leadership : ';
        $popover .= htmlentities($this->getLeadership(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p><p>Level : ';
        $popover .= htmlentities($this->getLevel(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p><p>Dice : ';
        $popover .= htmlentities($this->getDice(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</p>">';
        $popover .= htmlentities($this->getName(),ENT_QUOTES|ENT_HTML5);
        $popover .= '</a>';
        return $popover;
    }


}
