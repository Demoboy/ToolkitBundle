<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Generic entity to hold fields to store files locally on the server's hard
 * disk.
 *
 * @ORM\MappedSuperClass
 * @ORM\HasLifecycleCallbacks
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
abstract class BaseDocument {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="path", type="string", length=255)
     */
    protected $path;

    /**
     * The mime type of the upload
     *
     * @var string
     * @ORM\Column(type="string", length=25)
     */
    protected $mimeType;

    /**
     * @var file
     * @Assert\NotBlank(message="Please select a file to upload")
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * The extension of the file
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    protected $extension;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter a name for the file")
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Ironman\SecurityBundle\Entity\User")
     */
    protected $uploadedBy;

    /**
     * @var DateTime
     * @ORM\Column(name="uploadedDate", type="datetime")
     */
    protected $uploadedDate;

    /**
     * The checksum of the file
     *
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    protected $checksum;

    /**
     * Constructor
     */
    public function __construct() {
        $this->uploadedDate = new DateTime("NOW");
    }

    /**
     * toString
     *
     * @return string Document representation (Name)
     */
    public function __toString() {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get uploadedBy
     *
     * @return User
     */
    public function getUploadedBy() {
        return $this->uploadedBy;
    }

    /**
     * Get uploadedDate
     *
     * @return DateTime
     */
    public function getUploadedDate() {
        return $this->uploadedDate;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     * @return Document $file
     */
    public function setFile(UploadedFile $file) {
        $this->file = $file;
        $this->mimeType = $file->getMimeType();
        $this->checksum = md5(file_get_contents($file->getPath()));
        $this->extension = $file->guessExtension();
        
        if ($this->extension === null) {
            $this->extension = $file->getClientOriginalExtension();
        }
        
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Document
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Set uploadedBy
     *
     * @param User $uploadedBy
     * @return Document
     */
    public function setUploadedBy(User $uploadedBy) {
        $this->uploadedBy = $uploadedBy;
        return $this;
    }

    /**
     * Set uploadedDate
     *
     * @param DateTime $uploadedDate
     * @return Document
     */
    public function setUploadedDate(DateTime $uploadedDate) {
        $this->uploadedDate = $uploadedDate;
        return $this;
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

        $this->file = null;
    }

    /**
     * @return string
     */
    public function getUploadRootDir() {
        $path = $this->rootPath() . $this->getUploadDir();
        return $path;
    }

    abstract function rootPath();

    /**
     * @return string
     */
    abstract function getUploadDir();

    /**
     * @return type
     */
    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    /**
     * @return type
     */
    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
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

            $this->path = $date . "/" . $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    public function isImage() {
        return false;
    }

    /**
     * Get the value of The mime type of the upload
     *
     * @return string
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * Set the value of The mime type of the upload
     *
     * @param string mimeType
     *
     * @return self
     */
    public function setMimeType($value) {
        $this->mimeType = $value;

        return $this;
    }

    /**
     * Get the value of The checksum of the file
     *
     * @return string
     */
    public function getChecksum() {
        return $this->checksum;
    }

    /**
     * Set the value of The checksum of the file
     *
     * @param string checksum
     *
     * @return self
     */
    public function setChecksum($value) {
        $this->checksum = $value;

        return $this;
    }

    /**
     * Get the value of The extension of the file
     *
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * Set the value of The extension of the file
     *
     * @param string extension
     *
     * @return self
     */
    public function setExtension($value) {
        $this->extension = $value;

        return $this;
    }

    public function slug() {

        // Lower case the string and remove whitespace from the beginning or end
        $str = trim(strtolower($this->name));

        // Remove single quotes from the string
        $str = str_replace("'", "", $str);

        // Every character other than a-z, 0-9 will be replaced with a single dash (-)
        $str = preg_replace("/[^a-z0-9]+/", "-", $str);

        // Remove any beginning or trailing dashes
        return  trim($str);
    }

}
