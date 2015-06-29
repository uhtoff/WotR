<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of CardEntryDetails
 *
 * @author Russ
 * @ORM\Entity(repositoryClass="CardEntryDetailsRepository")
 * @ORM\Table(name="wotrnew.cardentrydetails")
 */
class CardEntryDetails {

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
     * @ORM\Column(type="integer")
     */
    private $copies;
    /**
     *
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $turn;
    /**
     *
     * @var integer
     * @ORM\ManyToOne(targetEntity="Scenario")
     */
    private $scenario;
    /**
     * @ORM\ManyToOne(targetEntity="CardDetails")
     */
    private $cardDetails;
    /**
     *
     * @ORM\ManyToOne(targetEntity="CardGroupDetails")
     */
    private $startGroup;

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
     * Set copies
     *
     * @param integer $copies
     * @return CardEntryDetails
     */
    public function setCopies($copies)
    {
        $this->copies = $copies;

        return $this;
    }

    /**
     * Get copies
     *
     * @return integer 
     */
    public function getCopies()
    {
        return $this->copies;
    }

    /**
     * Set turn
     *
     * @param integer $turn
     * @return CardEntryDetails
     */
    public function setTurn($turn)
    {
        $this->turn = $turn;

        return $this;
    }

    /**
     * Get turn
     *
     * @return integer 
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * Set scenario
     *
     * @param \Meldon\WotRBundle\Entity\Scenario $scenario
     * @return CardEntryDetails
     */
    public function setScenario(\Meldon\WotRBundle\Entity\Scenario $scenario = null)
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * Get scenario
     *
     * @return \Meldon\WotRBundle\Entity\Scenario 
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * Set cardDetails
     *
     * @param \Meldon\WotRBundle\Entity\CardDetails $cardDetails
     * @return CardEntryDetails
     */
    public function setCardDetails(\Meldon\WotRBundle\Entity\CardDetails $cardDetails = null)
    {
        $this->cardDetails = $cardDetails;

        return $this;
    }

    /**
     * Get cardDetails
     *
     * @return \Meldon\WotRBundle\Entity\CardDetails 
     */
    public function getCardDetails()
    {
        return $this->cardDetails;
    }

    /**
     * Set startGroup
     *
     * @param \Meldon\WotRBundle\Entity\CardGroupDetails $startGroup
     * @return CardEntryDetails
     */
    public function setStartGroup(\Meldon\WotRBundle\Entity\CardGroupDetails $startGroup = null)
    {
        $this->startGroup = $startGroup;

        return $this;
    }

    /**
     * Get startGroup
     *
     * @return \Meldon\WotRBundle\Entity\CardGroupDetails 
     */
    public function getStartGroup()
    {
        return $this->startGroup;
    }
}
