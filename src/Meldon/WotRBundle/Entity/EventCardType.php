<?php
namespace Meldon\WotRBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of EventCardType
 *
 * @author Russell
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.eventcardtype")
 */
class EventCardType {
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
     * @ORM\Column(length=25)
     */
    private $name;

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
     * @return EventCardType
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
}
