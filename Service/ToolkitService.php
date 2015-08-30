<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Service;

/**
 * Service class that creates FOSUser based on Symfony configs
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class ToolkitService
{

    /**
     * Used for Doctrine Fixtures, if set to true the default fixture is not loaded
     *
     * @var boolean 
     */
    private $overrideFixture;

    /**
     * The configs
     * 
     * @var array 
     */
    private $config;

    /**
     * The FOS user manager
     * @var FOS\UserBundle\Model\UserManager
     */
    protected $fosUM;

    /**
     * Basic constructor
     * @param array $config The configs
     * @param \FOS\UserBundle\Model\UserManager $fosUM The FOS User manager
     */
    public function __construct(array $config, \FOS\UserBundle\Model\UserManager $fosUM)
    {
        $this->config = $config;
        $this->fosUM = $fosUM;
        $this->overrideFixture = false;
        define("KMJ_TK_DISPLAY_COUNTRY", $config['display_country']);
    }

    /**
     * Responds to kernel requests to initalize service on start up 
     */
    public function onKernelRequest()
    {
        return;
    }

    /**
     * Creates a new user and returns it populated with
     * data from the configs
     * 
     * @return mixed
     */
    public function createAdminUser()
    {
        $user = $this->fosUM->createUser();

        $user->setFirstName($this->config['administrator']['firstname'])
            ->setLastName($this->config['administrator']['lastname'])
            ->setEmail($this->config['administrator']['email'])
            ->setPlainPassword($this->config['administrator']['password'])
            ->setEnabled(true)
            ->setUsername($this->config['administrator']['username']);

        return $user;
    }

    /**
     * Creates an array with user data for Alice fixtures
     * @return array
     */
    public function createAdminUserArray()
    {
        return array(
            "firstName" => $this->config['administrator']['firstname'],
            "lastName" => $this->config['administrator']['lastname'],
            "email" => $this->config['administrator']['email'],
            "plainPassword" => $this->config['administrator']['password'],
            "enabled" => true,
            "username" => $this->config['administrator']['username'],
        );
    }

    /**
     * Determines if a Doctrine Fixtures loading user accounts has already be loaded.
     * 
     * @param null|boolean $overrideFixture if boolean overridefixtures will be set to the value
     * @return boolean
     */
    public function overrideFixture($overrideFixture = null)
    {
        if ($overrideFixture === null) {
            return $this->overrideFixture;
        }

        $this->overrideFixture = $overrideFixture;
    }
}
