<?php
/**
 * This file is part of the IronmanAppBundle
 * @copyright (c) 2015, Ironman Reality LLC
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KMJ\ToolkitBundle\Entity\User;
use DateTime;

/**
 * BaseNote class that handles a typical note. Just extend and set author object
 * @ORM\MappedSuperclass()
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
abstract class BaseNote
{
    /**
     * @var integer ID for the entity
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DateTime The datetime the note was created
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string The text of the note
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * Basic clone function
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
        }
    }

    /**
     * Get the value of Author
     *
     * @return User The user that wrote the message
     */
    abstract public function getAuthor();

    /**
     * Set the value of Author
     *
     * @param User The user that wrote the message author
     *
     * @return self
     */
    abstract public function setAuthor($author);

    /**
     * Basic constructor
     */
    public function __construct()
    {
        $this->date = new DateTime();
    }

    /**
     * Get the value of Id
     *
     * @return integer ID for the entity
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of Date
     *
     * @return DateTime The datetime the note was created
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of Date
     *
     * @param DateTime The datetime the note was created date
     *
     * @return self
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of Text
     *
     * @return string The text of the note
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of Text
     *
     * @param string The text of the note text
     *
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}
