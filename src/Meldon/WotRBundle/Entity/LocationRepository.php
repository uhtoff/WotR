<?php
/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 29/05/2015
 * Time: 01:09
 */

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LocationRepository extends EntityRepository {
    public function getLocationsWithUnitsBySide($side)
    {
        return $this
            ->createQueryBuilder('l')
            ->leftJoin('l.units','u')
            ->where('u.side = :side')
            ->setParameter('side',$side)
            ->getQuery()
            ->getResult();
    }
}