<?php
namespace Meldon\WotRBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of HuntTile
 *
 * @author Russell
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.hunttile")
 */
class HuntTile extends CardDetails {
    /**
     *
     * @var string
     * @ORM\Column(length=2)
     */
    private $corruption;
    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $reveal;
    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $stop;
    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $special;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     *
     * @var string
     * @ORM\Column(length=25)
     */
    private $filename;

    /**
     * Set corruption
     *
     * @param string $corruption
     * @return HuntTile
     */
    public function setCorruption($corruption)
    {
        $this->corruption = $corruption;

        return $this;
    }

    /**
     * Get corruption
     *
     * @return string 
     */
    public function getCorruption()
    {
        return $this->corruption;
    }

    /**
     * Set reveal
     *
     * @param boolean $reveal
     * @return HuntTile
     */
    public function setReveal($reveal)
    {
        $this->reveal = $reveal;

        return $this;
    }

    /**
     * Get reveal
     *
     * @return boolean 
     */
    public function getReveal()
    {
        return $this->reveal;
    }

    /**
     * Set stop
     *
     * @param boolean $stop
     * @return HuntTile
     */
    public function setStop($stop)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * Get stop
     *
     * @return boolean 
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * Set special
     *
     * @param boolean $special
     * @return HuntTile
     */
    public function setSpecial($special)
    {
        $this->special = $special;

        return $this;
    }

    /**
     * Get special
     *
     * @return boolean 
     */
    public function getSpecial()
    {
        return $this->special;
    }
    /**
     * Set filename
     *
     * @param string $filename
     * @return HuntTile
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return HuntTile
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

    public function getCardType() {
        return 'Hunt';
    }
}
