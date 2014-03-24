<?php

namespace KMJ\ToolkitBundle\Service;

/**
 * Description of ToolkitService
 *
 * @author kaelinjacobson
 */
class ToolkitService {

    private $overrideFixture;
    private $config;
    protected $fosUM;

    public function __construct($config, $fosUM) {
        $this->config = $config;
        $this->fosUM = $fosUM;
        $this->overrideFixture = false;
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
 
    public function overrideFixture($overrideFixture = null) {
        if ($overrideFixture === null) {
            return $this->overrideFixture;
        }
        
        $this->overrideFixture = $overrideFixture;
    }

}
