<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KMJ\ToolkitBundle\Service\ToolkitService;

/**
 * Generic entity to hold fields to store files locally on the server's hard
 * disk.
 *
 * @ORM\Table(name="kmj_toolkit_docs_encrypted")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class EncryptedDocument extends BaseDocument
{
    use \KMJ\ToolkitBundle\Traits\EncryptedDocumentTrait;

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
        $toolkit = ToolkitService::getInstance();

        return $toolkit->getRootDir().'/Resources/protectedUploads/';
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
}
