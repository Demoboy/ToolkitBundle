<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Controller;

use KMJ\ToolkitBundle\Entity\BaseDocument;
use KMJ\ToolkitBundle\Entity\EncryptedDocument;
use KMJ\ToolkitBundle\Entity\HiddenDocument;
use KMJ\ToolkitBundle\Entity\S3EncryptedDocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Document controller
 * @Route("/documents")
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class DocumentController extends Controller
{

    /**
     * Provides a response to download a HiddenDocument
     * @Route("/download/hidden/{document}")
     * @Method({"GET"})
     * @param Document $document
     */
    public function downloadHiddenAction(HiddenDocument $document)
    {
        if (!file_exists($document->getAbsolutePath())) {
            throw $this->createNotFoundException("Document was not found");
        }

        return new BinaryFileResponse($document->getAbsolutePath());
    }

    /**
     * Creates a download type response for the decrypted file a secure document 
     * @Route("/download/encrypted/{document}")
     * @Method({"GET"})
     * @param SecureDocument $document
     */
    public function downloadEncryptedAction(EncryptedDocument $document)
    {
        $response = new Response();

        $doc = $this->getDecryptedDocument($document);

        $response->headers->set("Content-Type", "application/octet-stream");
        $response->headers->set("Content-Disposition",
            "attachment; filename={$document->slug()}.{$document->getExtension()}");
        $response->headers->set("Content-Length", strlen($doc));

        $response->setContent($doc);

        return $response;
    }

    /**
     * @Route("/download/s3/encrypted/{document}")
     * @Method({"GET"})
     * @param S3EncryptedDocument $document
     */
    public function downloadS3DocumentAction(S3EncryptedDocument $document)
    {
        $response = new Response();
        $s3Client = $this->get("kmj.aws.s3");

        @mkdir(dirname($document->getAbsolutePath()), "0664", true);

        $s3Client->getObject([
            "Bucket" => $this->getParameter("kmj_aws.s3.bucket"),
            "Key" => $document->getFileKey(),
            "SaveAs" => $document->getAbsolutePath(),
        ]);
        //file has been saved can now call decrypt on it
        $doc = $this->getDecryptedDocument($document);

        $response->headers->set("Content-Type", "application/octet-stream");
        $response->headers->set("Content-Disposition",
            "attachment; filename={$document->slug()}.{$document->getExtension()}");
        $response->headers->set("Content-Length", strlen($doc));

        $response->setContent($doc);

        return $response;
    }

    /**
     * Creates a download type response for the decrypted file a secure document 
     * @Route("/view/encrypted/{document}/{name}")
     * @Method({"GET"})
     * @param SecureDocument $document
     */
    public function viewEncryptedAction(EncryptedDocument $document,
                                        $name = null)
    {
        if ($name === null) {
            return $this->redirectToRoute("kmj_toolkit_document_viewencrypted",
                    array("document" => $document->getId(), "name" => $document->getName()));
        }

        $response = new Response();

        $doc = $this->getDecryptedDocument($document);

        $response->headers->set("Content-Type", $document->getMimeType());
        $response->headers->set("Content-Length", strlen($doc));

        $response->setContent($doc);

        return $response;
    }

    /**
     * Creates a view response for the document
     * @Route("/view/hidden/{document}/{name}")
     * @Method({"GET"})
     * @param HiddenDocument $document The document to view
     */
    public function viewHiddenAction(HiddenDocument $document, $name = null)
    {
        if ($name === null) {
            return $this->redirectToRoute("kmj_toolkit_document_viewhidden",
                    array("document" => $document->getId(), "name" => $document->getName()));
        }

        $response = new Response();

        $response->headers->set("Content-Type", $document->getMimeType());
        $response->headers->set("Content-Length",
            filesize($document->getAbsolutePath()));

        $response->setContent(file_get_contents($document->getAbsolutePath()));

        return $response;
    }

    /**
     * Gets the decryted document as a string
     * 
     * @param BaseDocument $document
     * @return string the decrypted document
     */
    private function getDecryptedDocument(BaseDocument $document)
    {
        $tk = $this->get("toolkit");
        $document->setKey($tk->getEncKey());
        return $document->decrypt();
    }
}