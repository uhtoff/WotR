<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 23/06/2015
 * Time: 12:15
 */

namespace Meldon\WotRBundle\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class NationCollection extends WotRCollection {
    /**
     * @var ArrayCollection
     */
    protected $nations;

    /**
     * @return ArrayCollection
     */
    public function getElements()
    {
        return $this->nations;
    }

    /**
     * @param ArrayCollection $elements
     */
    protected function setElements($elements)
    {
        $this->nations = $elements;
    }

    /**
     * @param bool $war
     * @return $this
     */
    public function filterByWar($war = true)
    {
        return $this->filter(
            function ($n) use($war) {
                return $n->atWar() == $war;
            }
        );
    }
}
