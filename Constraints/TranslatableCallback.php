<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Constraints;

use Symfony\Component\Validator\Constraints\Callback;

/**
 * Constraint that allows adding of a message so the JMSTranslationBundle can read it
 * 
 * @Annotation
 * @Target({"CLASS", "PROPERTY", "METHOD", "ANNOTATION"})
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class TranslatableCallback extends Callback
{
    /**
     * A translation key
     * 
     * @var string
     */
    public $message;

    /**
     * Basic constuctor
     * @param array $options Options for the callback
     */
    public function __construct($options = null)
    {
        if (isset($options['message']) && $options['message'] != "") {
            $this->message = $options['message'];
        }

        parent::__construct($options);
    }
}
