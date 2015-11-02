<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use KMJ\ToolkitBundle\Service\ToolkitService;
use Zend\Filter\Encrypt;

/**
 * Handles encrypting text in the database
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="kmj_toolkit_encrytedtext")
 */
class EncryptedText
{

    /**
     * The algorithm to use when encryting and decrypting the text
     */
    const ALGORITHM = "twofish";

    /**
     * The id of the object
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The encrypted text
     * @ORM\Column(type="text")
     * @var string
     */
    protected $encryptedText;

    /**
     * The salt to use for the encryption
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    protected $salt;

    /**
     * The raw unencrypted text
     * @var string
     */
    protected $rawText;

    /**
     * The encryption key
     * @var string
     */
    protected $key;

    /**
     * Basic Constructor
     * @param string $key The key to use to encrypt the file
     */
    public function __construct($key = null)
    {
        if ($key === null) {
            $this->key = ToolkitService::getInstance()->getEncKey();
        } elseif ($key != null) {
            $this->key = $key;
        } else {
            throw new InvalidArgumentException("Encryption key was not initalized");
        }

        $this->salt = $this->generateSalt();
    }

    /**
     * Generates a secure salt
     * @return string
     */
    private function generateSalt()
    {
        $salt = microtime();

        for ($i = 0; $i <= 100; $i++) {
            $salt = md5($salt);
        }

        return $salt;
    }

    /**
     * Encrypts the raw text to be stored in the database
     * @ORM\PrePersist()
     */
    public function encrypt()
    {
        if ($this->rawText !== null) {
            $zend = new Encrypt($this->getEncryptionOptions());
            $this->encryptedText = $zend->filter($this->rawText);
        }
    }

    /**
     * Decrypts the encrypted text and returns it
     * @return string
     */
    public function decrypt()
    {
        if ($this->key === null) {
            $this->key = ToolkitService::getInstance()->getEncKey();
        }
        $zend = new \Zend\Filter\Decrypt($this->getEncryptionOptions());
        return $zend->filter($this->encryptedText);
    }

    /**
     * Gets the encryption options to use when encrypting and decryting the string
     * @return array
     */
    private function getEncryptionOptions()
    {
        return array(
            "adapter" => "BlockCipher",
            "vector" => $this->getSalt(),
            "algorithm" => self::ALGORITHM,
            "key" => $this->key,
        );
    }

    /**
     * Get the value of The id of the object
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of The encrypted text
     *
     * @return string
     */
    public function getEncryptedText()
    {
        return $this->encryptedText;
    }

    /**
     * Set the value of The encrypted text
     *
     * @param string encryptedText
     *
     * @return self
     */
    public function setEncryptedText($encryptedText)
    {
        $this->encryptedText = $encryptedText;

        return $this;
    }

    /**
     * Get the value of The salt to use for the encryption
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set the value of The salt to use for the encryption
     *
     * @param string salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get the value of The raw unencrypted text
     *
     * @return string
     */
    public function getRawText()
    {
        return $this->rawText;
    }

    /**
     * Set the value of The raw unencrypted text
     *
     * @param string rawText
     *
     * @return self
     */
    public function setRawText($rawText)
    {
        $this->rawText = $rawText;

        return $this;
    }
}
