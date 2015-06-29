<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of PreRequisite
 *
 * @author Russ
 * @ORM\MappedSuperclass
 */


class PreRequisite {
    use ArgumentParser;
    /**
     * @ORM\Column(type="text")
     */
    private $method;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $arguments;
    /**
     * @ORM\Column(length=25)
     */
    private $operand;
    /**
     * @ORM\Column(length=100, nullable = true)
     */
    private $value;
    private $data;
    
    public function assess($data, $obj = NULL)
    {
        $methods = $this->parseMethods();
        $arguments = $this->parseArguments($obj);
        $this->data = $data;
        foreach( $methods as $methodNum => $method )
        {
            if ( !is_object($data) ) {
                return false;
            }
            if (method_exists($data, $method))
            {
                $data = call_user_func_array(array($data,$method),$arguments[$methodNum]);
            } elseif ( method_exists( $data, 'get' . ucfirst($method) ))
            {
                $arg = isset($arguments[$methodNum]) ? $arguments[$methodNum] : $arguments[0];
                $getMethod = 'get' . ucfirst($method);
                $data = call_user_func_array(array($data,$getMethod),$arg);
            } else
            {
                var_dump($data->getName() . ' - ' . $method);
                throw new PreRequsiteMethodNotFoundException();
            }
        }
        return $this->{'do' . $this->getOperand()}($data);
    }
    
    protected function doEquals($property)
    {
        return $property == $this->getValue();
    }
    
    protected function doGte($property)
    {
        return $property >= $this->getValue();
    }
    
    protected function doGt($property)
    {
        return $property > $this->getValue();
    }
    protected function doNull($property)
    {
        return is_null($property);
    }
    /**
     * Check if property is empty - if an array, use PHP empty function, if an object call isEmpty()
     * @param $property
     * @return bool
     */
    protected function doEmpty($property)
    {
        if ( is_array($property) ) {
            return empty($property) == $this->getValue();
        } if ( is_object($property) ) {
            return $property->isEmpty() == $this->getValue();
        }
    }

    protected function doIsEntity($property)
    {
        $v = __NAMESPACE__ . "\\" . $this->getValue();
        return is_a($property,$v);
    }

    protected  function doIsset($property)
    {
        return isset($property[$this->getValue()]);
    }
    /**
     * Set operand
     *
     * @param string $operand
     * @return PreRequisite
     */
    public function setOperand($operand)
    {
        $this->operand = $operand;

        return $this;
    }

    /**
     * Get operand
     *
     * @return string 
     */
    public function getOperand()
    {
        return $this->operand;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return PreRequisite
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * Set method
     *
     * @param string $method
     * @return PreRequisite
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
     * Set arguments
     *
     * @param string $arguments
     * @return PreRequisite
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
