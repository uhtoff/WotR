<?php

namespace Meldon\WotRBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UnitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UnitRepository extends EntityRepository
{
    public function getUnitsAtLocation($location)
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.location = :loc')
            ->setParameter('loc',$location)
            ->getQuery()
            ->getResult();
    }
}
