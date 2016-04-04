<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Service;

use FOS\UserBundle\Model\UserManagerInterface;
use Exception;

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
     * @var UserManagerInterface
     * 
     */
    protected $fosUM;

    /**
     * Creates a singleton
     * 
     * @var Singleton 
     */
    protected static $instance;

    /**
     * Basic constructor
     * @param array $config The configs
     * @param UserManagerInterface $fosUM The FOS User manager
     * @codeCoverageIgnore
     */
    public function __construct(array $config, UserManagerInterface $fosUM)
    {
        $this->config = $config;
        $this->fosUM = $fosUM;
        $this->overrideFixture = !$config['load_user_fixtures'];

        static::$instance = $this;
    }

    /**
     * Gets an initalized verson of self for a singleton
     * @return ToolkitService
     * @throws Exception
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            throw new Exception("Toolkit has not been initalized");
        }

        return static::$instance;
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

    /**
     * Responds to kernel requests to initalize service on start up 
     */
    public function onKernelRequest()
    {
        return;
    }

    /**
     * Return the encryption key
     * @return string
     */
    public function getEncKey()
    {
        return $this->config['enckey'];
    }

    /**
     * Return the root directory for file uploads
     * @return string
     */
    public function getRootDir()
    {
        return $this->config['rootdir'];
    }
}
