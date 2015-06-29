<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Decision
 *
 * @author Russ
 * @ORM\Entity
 * @ORM\Table(name="wotrnew.decision")
 */
class Decision {
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
    private $position;
    /**
     *
     * @ORM\Column(length=50, nullable=true)
     */
    private $type;
    /**
     *
     * @ORM\ManyToOne(targetEntity="Side")
     */
    private $side;
    /**
     *
     * @ORM\ManyToOne(targetEntity="DecisionStack", inversedBy="decisions", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $stack;
    /**
     * @ORM\ManyToOne(targetEntity="Action", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $action;
    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $data;
    /**
     * @ORM\Column(length=50, nullable=true)
     */
    private $actionMethod;
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
     * Set position
     *
     * @param integer $position
     * @return Decision
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }


    /**
     * Set side
     *
     * @param \Meldon\WotRBundle\Entity\Side $side
     * @return Decision
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
     * Set stack
     *
     * @param \Meldon\WotRBundle\Entity\DecisionStack $stack
     * @return Decision
     */
    public function setStack(\Meldon\WotRBundle\Entity\DecisionStack $stack = null)
    {
        $this->stack = $stack;
        $stack->addDecision($this);
        return $this;
    }

    /**
     * Get stack
     *
     * @return \Meldon\WotRBundle\Entity\DecisionStack 
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Decision
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->getAction()->getFormType();
    }
    
    public function __construct(Side $side, $type = NULL, $data = NULL)
    {
        $this->setSide($side);
        $this->setType($type);
        $this->setActionMethod($type);
        if ( is_array( $data ) && method_exists( $this, 'set' . ucfirst($data[0])))
        {
            $this->{'set' . ucfirst($data[0])}($data[1]);
        } else {
            $this->setData($data);
        }
    }

    /**
     * Set data
     *
     * @param \stdClass $data
     * @return Decision
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \stdClass 
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * Set action
     *
     * @param \Meldon\WotRBundle\Entity\Action $action
     * @return Decision
     */
    public function setAction(\Meldon\WotRBundle\Entity\Action $action = null)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return \Meldon\WotRBundle\Entity\Action 
     */
    public function getAction()
    {
        return $this->action;
    }


    /**
     * Set actionMethod
     *
     * @param string $actionMethod
     * @return Decision
     */
    public function setActionMethod($actionMethod)
    {
        $this->actionMethod = $actionMethod;

        return $this;
    }

    /**
     * Get actionMethod
     *
     * @return string 
     */
    public function getActionMethod()
    {
        return $this->getAction()->getActionMethod();
    }
    public function execute(Game $game, $data){
        $this->getAction()->execute($game, $data);
    }
}
