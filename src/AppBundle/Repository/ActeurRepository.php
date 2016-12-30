<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ActeurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActeurRepository extends EntityRepository
{
    public function getJeunesActeurs($max)
    {
        return $this
            ->createQueryBuilder('a')
            ->orderBy('a.dateNaissance', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }
}
