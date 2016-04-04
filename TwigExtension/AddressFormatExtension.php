<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KMJ\ToolkitBundle\TwigExtension;

use InvalidArgumentException;
use KMJ\ToolkitBundle\Entity\Address;
use KMJ\ToolkitBundle\Entity\Country;
use KMJ\ToolkitBundle\Entity\State;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Description of AddressFormatExtension
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.0
 */
class AddressFormatExtension extends Twig_Extension
{
    const INTERNATIONAL = "INTERNATIONAL";
    const NATIONAL = "NATIONAL";

    /**
     * Declare the asset_url function
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction("address_format", [$this, "addressFormat"],
                [ "is_safe" => ["html"]]),
        );
    }

    public function addressFormat(Address $address = null,
                                  $format = self::INTERNATIONAL)
    {
        if ($address === null) {
            return;
        }

        if (true === is_string($format)) {
            $constant = self::class."::".$format;
            if (false === defined($constant)) {
                throw new InvalidArgumentException(sprintf('The format must be either a constant value or name in %s',
                    self::class));
            }
            $format = constant(self::class."::".$format);
        }

        $zipcode = strip_tags($address->getZipcode());

        if ($address->getState() instanceof State) {
            $state = $address->getState()->getCode();
        } else {
            $state = null;
        }

        if ($address->getCountry() instanceof Country) {
            $country = $address->getCountry()->getCode();
        } else {
            $country = null;
        }

        $string = strip_tags($address->getStreet())."<br />";

        if ($address->getUnit() !== null) {
            $string .= strip_tags($address->getUnit())."<br />";
        }

        $string .= strip_tags($address->getCity())." ";

        if ($state !== null) {
            $string .= $state;

            if ($country !== null && $format === self::INTERNATIONAL) {
                $string .= ", ";
            }
        }

        if ($format === self::INTERNATIONAL) {
            if ($country !== null) {
                $string .= $country." ";
            }
        } else {
            $string .= " ";
        }

        $string .= $zipcode;

        return $string;
    }

    /**
     * Set a name for the extension
     */
    public function getName()
    {
        return 'address_format_extension';
    }
}
