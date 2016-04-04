<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Aws\S3\S3Client;
use Doctrine\ORM\Mapping as ORM;

/**
 * Generic entity to hold fields to store files locally on the server's hard
 * disk.
 *
 * @ORM\Table(name="kmj_toolkit_docs_encrypted_s3")
 * @ORM\Entity
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class S3EncryptedDocument extends BaseDocument
{

    use \KMJ\ToolkitBundle\Traits\EncryptedDocumentTrait;
    /**
     * The key for the file on S3
     *
     * @ORM\Column(type="text")
     * @var string
     */
    private $fileKey;

    public function __construct($key = null)
    {
        parent::__construct();
        $this->load($key);
    }

    /**
     * {@inheritdoc}
     */
    public function rootPath()
    {
        $toolkit = \KMJ\ToolkitBundle\Service\ToolkitService::getInstance();
        return $toolkit->getRootDir().'/Resources/protectedUploads/';
    }

    public function uploadToS3(S3Client $s3, $bucket, $key, $encrypt = true)
    {
        $this->preUpload();
        $this->uploadFile();

        $this->fileKey = $key."/{$this->path}";
        $absolutePath = $this->getAbsolutePath();

        $putRequest = [
            "Bucket" => $bucket,
            'Key' => $this->fileKey,
            'SourceFile' => $absolutePath,
            "ACL" => "private",
        ];

        if ($encrypt) {
            $putRequest["ServerSideEncryption"] = "AES256";
        }

        $promise = $s3->putObjectAsync($putRequest);

        return $promise;
    }

    public function __destruct()
    {
        //remove the file from the file system, if it wasn't save to s3 it will be gone forever
        @unlink($this->getAbsolutePath());
    }

    /**
     * Get the value of The key for the file on S3
     *
     * @return string
     */
    public function getFileKey()
    {
        return $this->fileKey;
    }

    /**
     * Set the value of The key for the file on S3
     *
     * @param string fileKey
     *
     * @return self
     */
    public function setFileKey($fileKey)
    {
        $this->fileKey = $fileKey;

        return $this;
    }
}