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
 * @ORM\Table(name="kmj_toolkit_docs_hidden")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class HiddenDocument extends BaseDocument
{

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
     */
    public function getUploadDir()
    {
        return "documents";
    }
}
