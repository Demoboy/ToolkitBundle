<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Repository;

use Doctrine\ORM\EntityRepository;
use KMJ\ToolkitBundle\Entity\Role;

/**
 * Repository class for User entity
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class UserRepository extends EntityRepository
{

    /**
     * Gets a user based on role
     * 
     * @param Role $role The role to search for users by
     * @param array $params Any extra arguments to search for the user by
     * @param booelan $single If true, only a single result is returned
     * @return mixed
     */
    public function findByRole(Role $role, $params = array(), $single = false)
    {
        $qb = $this->createQueryBuilder("u");

        $qb->innerJoin("u.userRoles", "r")
            ->andWhere("r = :role")
            ->setParameter("role", $role);

        $i = 1;
        foreach ($params as $key => $value) {
            $qb->andWhere(sprintf("u.%s = ?%d", $key, $i))
                ->setParameter($i, $value);
            $i++;
        }

        if ($single) {
            $qb->setMaxResults(1);
            return $qb->getQuery()->getSingleResult();
        }

        return $qb->getQuery()->getResult();
    }
}
