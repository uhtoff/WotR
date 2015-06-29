<?php
/**
 * Created by PhpStorm.
 * User: Russ
 * Date: 23/06/2015
 * Time: 12:07
 */

namespace Meldon\WotRBundle\Collection;

use Doctrine\Common\Collections\Criteria;
use Meldon\WotRBundle\Entity\Side;

class WotRCollection extends GameCollection {
    /**
     * Filter collection by those that have the requested side
     * @param Side $side
     * @return \Doctrine\Common\Collections\Collection
     */
    public function filterBySide(Side $side) {
//        $criteria = Criteria::create();
//        $expr = Criteria::expr();
//        $criteria->where($expr->eq('details.side',$side));
        $p = function($e) use ($side) {
            return $e->getSide() == $side;
        };
        return $this->filter($p);
    }
}