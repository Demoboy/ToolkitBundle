<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Generic entity to hold fields to store files locally on the server's hard
 * disk.
 *
 * @ORM\Table(name="kmj_toolkit_docs_encrypted_s3")
 * @ORM\Entity
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class S3EncryptedDocument extends S3Document
{
    use \KMJ\ToolkitBundle\Traits\EncryptedDocumentTrait;

    public function __construct($key = null)
    {
        parent::__construct();
        $this->load($key);
    }
}
