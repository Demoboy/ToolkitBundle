<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Hierarchy;

use Symfony\Component\Security\Core\Role\RoleHierarchy as SymfonyRoleHierarchy;

/**
 * Determines role hierarchy
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class RoleHierarchy extends SymfonyRoleHierarchy {

    /**
     * The entity manager
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;
    
    /**
     * Current role hierarchy, usually provided from configs
     * 
     * @var array 
     */
    private $existingHierarchy;

    /**
     * Basic constructor
     * @param array $hierarchy The current role hierarchy
     * @param \Doctrine\ORM\EntityManager $em The entity manager to use
     */
    public function __construct(array $hierarchy, \Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
        $this->existingHierarchy = $hierarchy;
        parent::__construct($this->buildRolesTree());
    }

    /**
     * Organize the roles into a hierarchal array
     * @return array
     */
    public function buildRolesTree() {
        $hierarchy = array();
        $roles = $this->em->getRepository("KMJToolkitBundle:Role")->findAll();

        foreach ($roles as $role) {
            /** @var $role Role */
            if ($role->getParent()) {
                if (!isset($hierarchy[$role->getParent()->getName()])) {
                    $hierarchy[$role->getParent()->getName()] = array();
                }
                $hierarchy[$role->getParent()->getName()][] = $role->getName();
            } else {
                if (!isset($hierarchy[$role->getName()])) {
                    $hierarchy[$role->getName()] = array();
                }
            }
        }

        return array_merge_recursive($hierarchy, $this->existingHierarchy);
    }

}
