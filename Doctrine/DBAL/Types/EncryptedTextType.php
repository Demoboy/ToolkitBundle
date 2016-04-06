<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2016, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use KMJ\ToolkitBundle\Service\ToolkitService;
use Zend\Filter\Decrypt;
use Zend\Filter\Encrypt;

/**
 * Doctrine type that encrypts the provided string for storage in the database.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since 1.2
 */
class EncryptedTextType extends Type
{
    /**
     * The salt to use to encrypt the text with.
     *
     * @var string
     */
    private $salt;

    public function getName()
    {
        return 'encrypted_text';
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return;
        }
        //ensure the salt is null
        $this->setSalt(null);

        $zend = new Encrypt($this->getEncryptionOptions());
        $encryptedText = $zend->filter($value);

        return sprintf('%s;%s', $encryptedText, $this->getSalt());
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        list($encryptedText, $this->salt) = explode(';', $value);
        $zend = new Decrypt($this->getEncryptionOptions());

        return $zend->filter($encryptedText);
    }

    /**
     * Gets the encryption options to use when encrypting and decryting the string.
     *
     * @return array
     */
    private function getEncryptionOptions()
    {
        return [
            'adapter' => 'BlockCipher',
            'vector' => $this->getSalt(),
            'algorithm' => 'twofish',
            'key' => ToolkitService::getInstance()->getEncKey(),
        ];
    }

    /**
     * Get the value of The salt to use to encrypt the text with.
     *
     * @return string
     */
    private function getSalt()
    {
        if ($this->salt === null) {
            $this->setSalt(bin2hex(openssl_random_pseudo_bytes(32)));
        }

        return $this->salt;
    }

    /**
     * Set the value of The salt to use to encrypt the text with.
     *
     * @param string salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }
}
