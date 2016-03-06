<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Hierarchy;

use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\Security\Core\Role\RoleHierarchy as SymfonyRoleHierarchy;

/**
 * Determines role hierarchy
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class RoleHierarchy extends SymfonyRoleHierarchy
{

    /**
     * The entity manager
     *
     * @var EntityManager 
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
     * @param EntityManager $em The entity manager to use
     */
    public function __construct(array $hierarchy, EntityManager $em)
    {
        $this->em = $em;
        $this->existingHierarchy = $hierarchy;
    }
    
    public function getReachableRoles(array $roles)
    {
        if (sizeof($this->map) === 0) {
            $this->buildRoleMap();
        }
        
        return parent::getReachableRoles($roles);
    }
    
    protected function buildRoleMap()
    {
        $this->map = array();
        
        $hierarchy = $this->buildRolesTree();
        
        foreach ($hierarchy as $main => $roles) {
            $this->map[$main] = $roles;
            $visited = array();
            $additionalRoles = $roles;
            while ($role = array_shift($additionalRoles)) {
                if (!isset($hierarchy[$role])) {
                    continue;
                }

                $visited[] = $role;
                $this->map[$main] = array_unique(array_merge($this->map[$main], $hierarchy[$role]));
                $additionalRoles = array_merge($additionalRoles, array_diff($hierarchy[$role], $visited));
            }
        }
    }

    /**
     * Organize the roles into a hierarchal array
     * @return array
     */
    private function buildRolesTree()
    {
        $hierarchy = array();

        try {
            $roles = $this->em->getRepository("KMJToolkitBundle:Role")->findAll();
        } catch (Exception $exc) {
            $roles = [];
        }

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