<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Zend\Filter\Decrypt;
use Zend\Filter\File\Encrypt;

/**
 * Generic entity to hold fields to store files locally on the server's hard
 * disk.
 *
 * @ORM\Table(name="kmj_toolkit_docs_encrypted")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class EncryptedDocument extends BaseDocument {

    const ALGORITHM = "twofish";

    public function rootPath() {
        return KMJTK_ROOT_DIR.'/Resources/protectedUploads/';
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
    public function __construct($key) {
        parent::__construct();
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getUploadDir() {
        return 'encrypted';
    }

    /**
     * Decrypts the document and returns the ninary content
     */
    public function decrypt() {
        if ($this->key === null) {
            throw new InvalidArgumentException("Key must be set before attempting to decrypt");
        }
       
        $decrypt = new Decrypt($this->getZendEncryptOptions());
        $x =  $decrypt->filter(file_get_contents($this->getAbsolutePath()));
        dump($x);
        return $x;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadPath() {
        if ($this->getFile() == null) {
            return;
        }

        $date = date("Y-m-d");

        $this->getFile()->move(
                $this->getUploadRootDir() . "/{$date}", $this->path
        );

        $encrypt = new Encrypt($this->getZendEncryptOptions());
        dump($encrypt->filter($this->getAbsolutePath()));

        $this->file = null;
    }

    private function getZendEncryptOptions() {
        $x = array(
            "adapter" => "BlockCipher",
            "vector" => $this->getChecksum(),
            "algorithm" => self::ALGORITHM,
            "key" => $this->key,
            "compression" => "bz2",
        );

        dump($x);
        return $x;
    }

    public function setKey($key) {
        $this->key = $key;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->getFile()) {
            $filename = sha1(uniqid(mt_rand(), true));
            $date = date("Y-m-d");
            @mkdir($this->getUploadRootDir() . "/" . $date, 0777, true);

            $this->path = $date . "/" . $filename;
        }
    }

}
