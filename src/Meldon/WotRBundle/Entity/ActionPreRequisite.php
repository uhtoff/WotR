<?php

namespace Meldon\WotRBundle\Entity;

use Meldon\WotRBundle\Entity\PreRequisite;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ActionPreRequisite
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="actionprerequisite")
 */
class ActionPreRequisite extends PreRequisite {

    /**
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

}
