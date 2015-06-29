<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 19/05/2015
 * Time: 22:54
 */

namespace Meldon\WotRBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="logitem")
 * @ORM\HasLifeCycleCallbacks
 */
 
class LogItem  {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Log",inversedBy="logItems")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $log;
    /**
     * @ORM\ManyToOne(targetEntity="ActionDetails")
     */
    private $actionDetails;
    /**
     * @ORM\ManyToOne(targetEntity="ActionDieDetails")
     */
    private $actionDie;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;
    /**
     * @ORM\Column(type="integer")
     */
    private $turn;
    /**
     * @ORM\Column(type="text")
     */
    private $phase;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return LogItem
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Return a formatted date from the CreatedAt property
     * @return string
     */
    public function getDate()
    {
        return $this->getCreatedAt()->format('Y-m-d H:i');
    }


    /**
     * Set log
     *
     * @param Log $log
     * @return LogItem
     */
    public function setLog(Log $log = null)
    {
        $this->log = $log;
        $log->addLogItem($this);
        return $this;
    }

    /**
     * Get log
     *
     * @return Log
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set text
     *
     * @param string $text
     * @param bool $break
     * @return LogItem
     */
    public function setText($text, $break = true)
    {
        if ( $this->getText() ) {
            $this->text = $this->getText();
            if ($break) {
                $this->text .= '<br/>';
            }
            $this->text .= $text;
        } else {
            $this->text = $text;
        }
        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set actionDie
     *
     * @param ActionDieDetails $actionDie
     * @return LogItem
     */
    public function setActionDie(ActionDieDetails $actionDie = null)
    {
        $this->actionDie = $actionDie;

        return $this;
    }

    /**
     * Get actionDie
     *
     * @return ActionDieDetails
     */
    public function getActionDie()
    {
        return $this->actionDie;
    }

    public function display()
    {
        echo "<p>";
        echo "Turn:" . $this->getTurn();
        if ( $this->getActionDetails() )
        {
            echo $this->getActionDetails()->getName();
        }
        if ( $this->getActionDie() )
        {
            echo $this->getActionDie()->getName();
        }
        if ( $this->getDecision() )
        {
            echo $this->getDecision();
        }
        if ( $this->getText() )
        {
            echo $this->getText();
        }
        echo $this->getCreatedAt()->format('}
            H:i');
        echo "</p>";
    }



    /**
     * Set turn
     *
     * @param integer $turn
     * @return LogItem
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
     * Set phase
     *
     * @param integer $phase
     * @return LogItem
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return integer 
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * Set actionDetails
     *
     * @param ActionDetails $actionDetails
     * @return LogItem
     */
    public function setActionDetails(ActionDetails $actionDetails = null)
    {
        $this->actionDetails = $actionDetails;

        return $this;
    }

    /**
     * Get actionDetails
     *
     * @return ActionDetails
     */
    public function getActionDetails()
    {
        return $this->actionDetails;
    }
}
