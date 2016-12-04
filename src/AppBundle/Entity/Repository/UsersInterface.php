<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Users;
use Doctrine\ORM\EntityRepository;

/**
 * Interface UsersInterface
 */
interface UsersInterface
{
    /**
     * @return Users|[]
     */
    public function getAllUsers();
}
