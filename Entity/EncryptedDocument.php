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

    use \KMJ\ToolkitBundle\Traits\EncryptedDocumentTrait;

    /**
     * {@inheritdoc}
     */
    public function rootPath()
    {
        $toolkit = ToolkitService::getInstance();
        return $toolkit->getRootDir() . '/Resources/protectedUploads/';
    }

    /**
     * {@inheritdoc}
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        parent::preUpload();
    }

    /**
     * {@inheritdoc}
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadFile()
    {
        parent::uploadFile();
    }
}
