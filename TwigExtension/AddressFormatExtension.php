<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace KMJ\ToolkitBundle\TwigExtension;

use Twig_Extension;
use Twig_SimpleFunction;
use KMJ\ToolkitBundle\Entity\Address;

/**
 * Description of AddressFormatExtension
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.0
 */
class AddressFormatExtension extends Twig_Extension
{

    /**
     * Declare the asset_url function
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction("address_format", [$this, "addressFormat"], [ "is_safe" => ["html"]]),
        );
    }

    public function addressFormat(Address $address)
    {
        $zipcode = strip_tags($address->getZipcode());

        if ($address->getState() instanceof \KMJ\ToolkitBundle\Entity\State) {
            $state = $address->getState()->getCode();
        } else {
            $state = null;
        }

        if ($address->getCountry() instanceof \KMJ\ToolkitBundle\Entity\Country) {
            $country = $address->getCountry()->getCode();
        } else {
            $country = null;
        }

        $string = strip_tags($address->getStreet()) . "<br />";

        if ($address->getUnit() !== null) {
            $string .= strip_tags($address->getUnit()) . "<br />";
        }

        $string .= strip_tags($address->getCity()) . " ";

        if ($state !== null) {
            $string .= $state;

            if ($country !== null) {
                $string .= ", ";
            }
        }

        if ($country !== null) {
            $string .= $country . " ";
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
