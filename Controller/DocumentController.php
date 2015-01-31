<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Controller;

use Ironman\SecurityBundle\Entity\Document;
use KMJ\ToolkitBundle\Entity\BaseDocument;
use KMJ\ToolkitBundle\Entity\EncryptedDocument;
use KMJ\ToolkitBundle\Entity\HiddenDocument;
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
class DocumentController extends Controller {

    /**
     * Provides a response to download a HiddenDocument
     * @Route("/download/hidden/{document}")
     * @Method({"GET"})
     * @param Document $document
     */
    public function downloadHiddenAction(HiddenDocument $document) {
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
    public function downloadEncryptedAction(EncryptedDocument $document) {
        $response = new Response();
        
        $doc = $this->getDecryptedDocument($document);

        $response->headers->set("Content-Type", "application/octet-stream");
        $response->headers->set("Content-Disposition", "attachment; filename={$document->slug()}.{$document->getExtension()}");
        $response->headers->set("Content-Length", strlen($doc));

        $response->setContent($doc);

        return $response;
    }
    
    /**
     * Creates a download type response for the decrypted file a secure document 
     * @Route("/view/encrypted/{document}")
     * @Method({"GET"})
     * @param SecureDocument $document
     */
    public function viewEncryptedAction(EncryptedDocument $document) {
        $response = new Response();
        
        $doc = $this->getDecryptedDocument($document);

        $response->headers->set("Content-Type", $document->getMimeType());
        $response->headers->set("Content-Length", strlen($doc));

        $response->setContent($doc);

        return $response;
    }
    
    /**
     * Gets the decryted document as a string
     * 
     * @param BaseDocument $document
     * @return string the decrypted document
     */
    private function getDecryptedDocument(BaseDocument $document) {
        $document->setKey($this->container->getParameter("ironman.documents.encryptionkey"));
        return $document->decrypt();
    }

}
