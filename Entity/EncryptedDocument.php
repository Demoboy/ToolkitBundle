<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use Zend\Filter\Decrypt;
use Zend\Filter\File\Encrypt;
use KMJ\ToolkitBundle\Service\ToolkitService;

/**
 * Generic entity to hold fields to store files locally on the server's hard
 * disk.
 *
 * @ORM\Table(name="kmj_toolkit_docs_encrypted")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class EncryptedDocument extends BaseDocument
{

    /**
     * Algorithm to use when encrypting the document
     */
    const ALGORITHM = "twofish";

    /**
     * {@inheritdoc}
     */
    public function rootPath()
    {
        $toolkit = ToolkitService::getInstance();
        return $toolkit->getRootDir() . '/Resources/protectedUploads/';
    }

    /**
     * The key to use to encrypt the file
     *
     * @var string
     */
    protected $key;

    /**
     * Basic Constructor
     * @param string $key The key to use to encrypt the file
     */
    public function __construct($key = null)
    {
        parent::__construct();

        if ($key === null) {
            $toolkit = ToolkitService::getInstance();
            $this->key = $toolkit->getEncKey();
        } elseif ($key != null) {
            $this->key = $key;
        } else {
            throw new \InvalidArgumentException("Encryption key was not initalized");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadDir()
    {
        return 'encrypted';
    }

    /**
     * Decrypts the document and returns the ninary content
     */
    public function decrypt()
    {
        if ($this->key === null) {
            throw new InvalidArgumentException("Key must be set before attempting to decrypt");
        }

        $decrypt = new Decrypt($this->getZendEncryptOptions());
        return $decrypt->filter(file_get_contents($this->getAbsolutePath()));
    }

    /**
     * {@inheritdoc}
     * 
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadFile()
    {
        parent::uploadFile();

        $encrypt = new Encrypt($this->getZendEncryptOptions());
        $encrypt->filter($this->getAbsolutePath());
    }

    /**
     * Gets the options to use when encrypting documents
     * 
     * @return array
     */
    private function getZendEncryptOptions()
    {
        return array(
            "adapter" => "BlockCipher",
            "vector" => $this->getChecksum(),
            "algorithm" => self::ALGORITHM,
            "key" => $this->key,
            "compression" => "bz2",
        );
    }

    /**
     * Sets the encryption key
     * 
     * @param string $key The string to use as an encryption key
     * @return EncryptedDocument
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
}
