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
 * @ORM\MappedSuperclass
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
abstract class BaseDocument
{
    /**
     * The id of the object 
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The path of the document on the server
     * 
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
     * The file to be uploaded 
     * 
     * @var file
     * @Assert\NotBlank(message="kmjtoolkit.basedoc.file.validation.notblank.message")
     * @Assert\File(maxSize="6000000")
     * @Assert\Image(groups={"image-only"})
     */
    protected $file;

    /**
     * The extension of the file
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    protected $extension;

    /**
     * The name of the document
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * The date the document was uploaded
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
    public function __construct()
    {
        $this->uploadedDate = new DateTime("NOW");
    }

    /**
     * toString
     *
     * @return string Document representation (Name)
     */
    public function __toString()
    {
        if ($this->name === null) {
            return "unknown";
        }

        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get uploadedDate
     *
     * @return DateTime
     */
    public function getUploadedDate()
    {
        return $this->uploadedDate;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     * @return Document
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        $this->mimeType = $file->getMimeType();
        $this->checksum = md5(file_get_contents($file->getRealPath()));
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
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set uploadedDate
     *
     * @param DateTime $uploadedDate
     * @return Document
     */
    public function setUploadedDate(DateTime $uploadedDate)
    {
        $this->uploadedDate = $uploadedDate;
        return $this;
    }

    /**
     * Moves $this->file to the filesystem
     */
    public function uploadFile()
    {
        if ($this->getFile() === null) {
            return;
        }

        $date = date("Y-m-d");

        $this->getFile()->move(
            $this->getUploadRootDir()."/{$date}", $this->path
        );

        $this->file = null;
    }

    /**
     * Gets the root directory where uploads should be placed
     * 
     * @return string
     */
    public function getUploadRootDir()
    {
        $path = $this->rootPath().$this->getUploadDir();
        return $path;
    }

    /**
     * The root path of the document
     */
    abstract function rootPath();

    /**
     * The upload directory added to the root durring uploads
     * 
     * @return string
     */
    abstract function getUploadDir();

    /**
     * Gets the absolute path to the file
     * 
     * @return string|null
     */
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * Prepares the file for being moved by making directories if they dont 
     * exist and determining the filename of the file.
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $filename = sha1(uniqid(mt_rand(), true));
            $date = date("Y-m-d");
            @mkdir($this->getUploadRootDir()."/".$date, 0777, true);

            $this->path = $date."/".$filename.'.'.$this->getFile()->guessExtension();

            if ($this->getName() === null) {
                $this->name = $this->file->getClientOriginalName();
            }
        }
    }

    /**
     * Get the value of The mime type of the upload
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set the value of The mime type of the upload
     *
     * @param string mimeType
     *
     * @return self
     */
    public function setMimeType($value)
    {
        $this->mimeType = $value;

        return $this;
    }

    /**
     * Get the value of The checksum of the file
     *
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * Set the value of The checksum of the file
     *
     * @param string checksum
     *
     * @return self
     */
    public function setChecksum($value)
    {
        $this->checksum = $value;

        return $this;
    }

    /**
     * Get the value of The extension of the file
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set the value of The extension of the file
     *
     * @param string extension
     *
     * @return self
     */
    public function setExtension($value)
    {
        $this->extension = $value;

        return $this;
    }

    /**
     * Slugifies the name of the document for URL generation
     * @return string
     */
    public function slug()
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $this->name);
        // trim
        $text = trim($text, '-');
        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
