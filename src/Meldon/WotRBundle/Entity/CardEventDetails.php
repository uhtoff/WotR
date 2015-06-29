<?php
namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 19/05/2015
 * Time: 12:07
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.cardeventdetails")
 */
class CardEventDetails
{
    use ArgumentParser;
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="text")
     */
    private $method;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $arguments;

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
     * Set method
     *
     * @param string $method
     * @return CardEvent
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    public function parseMethods()
    {
        return explode('.', $this->getMethod());
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->preReq = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add preReq
     *
     * @param \Meldon\WotRBundle\Entity\EventPreRequisite $preReq
     * @return CardEvent
     */
    public function addPreReq(\Meldon\WotRBundle\Entity\EventPreRequisite $preReq)
    {
        $this->preReq[] = $preReq;

        return $this;
    }

    /**
     * Remove preReq
     *
     * @param \Meldon\WotRBundle\Entity\EventPreRequisite $preReq
     */
    public function removePreReq(\Meldon\WotRBundle\Entity\EventPreRequisite $preReq)
    {
        $this->preReq->removeElement($preReq);
    }

    /**
     * Get preReq
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreReq()
    {
        return $this->preReq;
    }

    /**
     * Set arguments
     *
     * @param string $arguments
     * @return CardEventDetails
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Get arguments
     *
     * @return string 
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
