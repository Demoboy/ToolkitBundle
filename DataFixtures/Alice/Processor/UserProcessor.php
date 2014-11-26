<?php

namespace KMJ\ToolkitBundle\DataFixtures\Alice\Processor;

use FOS\UserBundle\Model\User;
use FOS\UserBundle\Model\UserManagerInterface;
use Nelmio\Alice\ProcessorInterface;

class UserProcessor implements ProcessorInterface {

    /**
     * @var UserManagerInterface The FOS User manager
     */
    protected $fosUser;

    public function __construct(UserManagerInterface $fosUser) {
        $this->fosUser = $fosUser;
    }

    public function preProcess($object) {
        return;
    }

    public function postProcess($object) {
        if (!$object instanceof User) {
            return;
        }

        $this->fosUser->updateUser($object, true);
    }

}
