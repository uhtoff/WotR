<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of PoliticalStep
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.politicalstep")
 */
class PoliticalStep {
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
     * @ORM\Column(length=50)
     */
    private $name;
    /**
     *
     * @var boolean
     */
    private $allowRecruitment;
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
     * @return PoliticalStep
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
    * 
    * @return boolean
    */
    public function atWar()
    {
        if ( $this->getName() == "At War" )
        {
            return true;
        } else 
        {
            return false;
        }
    }
    
    public function oneStepFromWar()
    {
        if ( $this->getName() == "1 Step From War" )
        {
            return true;
        } else
        {
            return false;
        }
    }

}
