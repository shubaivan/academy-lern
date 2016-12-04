<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UsersRepository
 */
class UsersRepository extends EntityRepository implements UsersInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAllUsers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('u')
            ->from('AppBundle:Users', 'u')
            ->where($qb->expr()->andX('u.startLearn IS NOT NULL', 'u.endLearn IS NOT NULL'))
            ->orderBy('u.score', 'DESC')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }
}
