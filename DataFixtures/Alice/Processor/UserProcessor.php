<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\DataFixtures\Alice\Processor;

use FOS\UserBundle\Model\User;
use FOS\UserBundle\Model\UserManagerInterface;
use Nelmio\Alice\ProcessorInterface;

/**
 * Looks at all items that are being persisted through Alice Fixtures
 * and if is an object is an instance of FOS\UserBundle\Model\User, the object
 * will have the fos user manager called to update the object
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class UserProcessor implements ProcessorInterface {

    /**
     * @var UserManagerInterface The FOS User manager
     */
    protected $fosUser;

    /**
     * Basic Constructor
     *
     * @param UserManagerInterface $fosUser The User Manager
     */
    public function __construct(UserManagerInterface $fosUser) {
        $this->fosUser = $fosUser;
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     * @param object $object instance to process
     */
    public function preProcess($object) {

    }

    /**
     * {@inheritDoc}
     *
     * @param object $object instance to process
     */
    public function postProcess($object) {
        if (!$object instanceof User) {
            return false;
        }

        $this->fosUser->updateUser($object, true);
        return true;
    }

}
