<?php

namespace KMJ\ToolkitBundle\Traits;

use InvalidArgumentException;
use KMJ\ToolkitBundle\Service\ToolkitService;
use Zend\Filter\Decrypt;
use Zend\Filter\File\Encrypt;

/**
 * Description of EncryptedDocumentTrait
 *
 * @author Kaelin Jacobson <kaelin@supercru.com>
 * @since 1.0
 */
trait EncryptedDocumentTrait
{

    abstract function getChecksum();

    /**
     * {@inheritdoc}
     */
    public function rootPath()
    {
        $toolkit = ToolkitService::getInstance();
        return $toolkit->getRootDir().'/Resources/protectedUploads/';
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
    public function load($key = null)
    {
        if ($key === null) {
            $toolkit = ToolkitService::getInstance();
            $this->key = $toolkit->getEncKey();
        } elseif ($key !== null) {
            $this->key = $key;
        } else {
            throw new InvalidArgumentException("Encryption key was not initalized");
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
            $toolkit = ToolkitService::getInstance();
            $this->key = $toolkit->getEncKey();
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
            "algorithm" => "twofish",
            "key" => $this->key,
            "compression" => "bz2",
        );
    }

    /**
     * Sets the encryption key
     * 
     * @param string $key The string to use as an encryption key
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
}