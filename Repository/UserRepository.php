<?php

namespace KMJ\ToolkitBundle\Repository;

use Doctrine\ORM\EntityRepository;
use KMJ\ToolkitBundle\Entity\Role;

class UserRepository extends EntityRepository
{

    public function findByRole(Role $role, $single = false)
    {
        $qb = $this->createQueryBuilder("u");

        $qb->innerJoin("u.userRoles", "r")
            ->andWhere("r = :role")
            ->setParameter("role", $role);

        if ($single) {
            $qb->setMaxResults(1);
            return $qb->getQuery()->getSingleResult();
        }

        return $qb->getQuery()->getResult();
    }

    public function findByRoleAnd($role, $params, $single = false)
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
