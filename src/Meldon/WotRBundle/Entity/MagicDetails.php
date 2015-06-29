<?php
namespace Meldon\WotRBundle\Entity;

/**
 * Description of MagicDetails
 *
 * @author Russ
 */
trait MagicDetails {

    public function getDetails()
    {
        return $this->details;
    }
    
    public function getDetailsID()
    {
        return $this->getDetails()->getID();
    }
    
    public function __call($name, $arguments)
    {
        $details = $this->getDetails();
        if (isset($details) && is_object($details) && method_exists($details, $name)) {
            return call_user_func_array(array($details,$name),$arguments);
        }
    }

}
