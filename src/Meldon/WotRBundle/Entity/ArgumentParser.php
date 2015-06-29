<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 19/05/2015
 * Time: 10:19
 */

namespace Meldon\WotRBundle\Entity;

/**
 * Class ArgumentParser
 * @package Meldon\WotRBundle\Entity *
 * @property $arguments string
 */

trait ArgumentParser {

    /**
     * Set arguments
     *
     * @param string $arguments
     * @return mixed
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

    /**
     * @param $obj
     * @param $methods
     * @return array
     */
    public function parseArguments($obj = NULL)
    {
        if ( $this->getArguments() )
        {
            $arguments = explode('.',$this->getArguments());
            foreach( $arguments as $key => $value )
            {
                if ( $value === 'NULL' || is_null($value) )
                {
                    $arguments[$key] = array(NULL);
                } elseif ( $value === 'this' )
                {
                    $arguments[$key] = array($obj);
                } else
                {
                    $arguments[$key] = explode(',',$arguments[$key]);
                    foreach( $arguments[$key] as $k => $a )
                    {
                        if ( $a === 'NULL' )
                        {
                            $arguments[$key][$k] = NULL;
                        } elseif ( $a === 'this' )
                        {
                            $arguments[$key][$k] = $obj;
                        }
                   }
                }
            }
        } else
        {
            $arguments = array_fill(0,count($this->parseMethods()),array(NULL));
        }
        return $arguments;
    }
}