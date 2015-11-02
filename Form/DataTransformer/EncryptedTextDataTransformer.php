<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Description of EncryptedTextDataTransformer
 *
 * @author kaelinjacobson
 */
class EncryptedTextDataTransformer implements DataTransformerInterface
{

    /**
     * The algorithm to use when encryting and decrypting the text
     */
    const ALGORITHM = "twofish";

    /**
     * The encryption key
     * @var string
     */
    protected $key;

    /**
     * Basic constructor
     * @param string $key The encryption key to use
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    public function reverseTransform($value)
    {
        
    }

    public function transform($value)
    {
        return $value->decrypt();
    }

    private function getEncryptionOptions()
    {
        return array(
            "adapter" => "BlockCipher",
            "vector" => $this->getChecksum(),
            "algorithm" => self::ALGORITHM,
            "key" => $this->key,
            "compression" => "bz2",
        );
    }
}
