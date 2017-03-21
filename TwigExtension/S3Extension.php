<?php
/**
 * This file is part of the BarcodeBundle.
 *
 * @copyright (c) 2017, Electronic Responsible Recyclers
 */

namespace KMJ\ToolkitBundle\TwigExtension;

use Aws\S3\S3Client;
use KMJ\ToolkitBundle\Entity\S3Document;
use Twig_Extension;

/**
 * Created by IntelliJ IDEA.
 * User: kaelin
 * Date: 3/21/17
 * Time: 11:03 AM.
 */
class S3Extension extends Twig_Extension
{
    /**
     * @var S3Client
     */
    private $s3;

    /**
     * @var string
     */
    private $bucket;

    /**
     * S3Extension constructor.
     *
     * @param S3Client $s3
     * @param string   $bucket
     */
    public function __construct(S3Client $s3, $bucket)
    {
        $this->s3 = $s3;
        $this->bucket = $bucket;
    }

    /**
     * Gets the filters that this extension will respond to.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('s3_signed_url', [$this, 'createS3SignedUrl']),
        ];
    }

    public function createS3SignedUrl(S3Document $doc)
    {
        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $doc->getFileKey(),
        ]);

        $request = $this->s3->createPresignedRequest($cmd, '+5 minutes');

        return (string) $request->getUri();
    }
}
