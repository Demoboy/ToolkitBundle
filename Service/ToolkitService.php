<?php

namespace KMJ\ToolkitBundle\Service;

/**
 * Description of ToolkitService
 *
 * @author kaelinjacobson
 */
class ToolkitService {

    private $config;
    protected $fosUM;

    public function __construct($config, $fosUM) {
        $this->config = $config;
        $this->fosUM = $fosUM;
    }

    public function createAdminUser() {
        $user = $this->fosUM->createUser();
        
        $user->setFirstName($this->config['administrator']['firstname'])
                ->setLastName($this->config['administrator']['lastname'])
                ->setEmail($this->config['administrator']['email'])
                ->setPlainPassword($this->config['administrator']['password'])
                ->setEnabled(true)
                ->setUsername($this->config['administrator']['username']);

        return $user;
    }

}
