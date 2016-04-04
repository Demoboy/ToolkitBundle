<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KMJ\ToolkitBundle\Service\ToolkitService;

/**
 * Generic entity to hold fields to store files locally on the server's hard
 * disk.
 *
 * @ORM\Table(name="kmj_toolkit_docs_web")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class WebDocument extends BaseDocument
{

    /**
     * {@inheritdoc}
     */
    public function rootPath()
    {
        $toolkit = ToolkitService::getInstance();
        return $toolkit->getRootDir().'/../web/';
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadDir()
    {
        return "uploads/documents";
    }

    /**
     * Gets the path for file visible from the web directory
     * 
     * @return string|null The path from the web directory
     */
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
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
