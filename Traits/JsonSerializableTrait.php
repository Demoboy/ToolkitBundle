<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2018, Kaelin Jacobson
 */

declare(strict_types=1);

namespace KMJ\ToolkitBundle\Traits;

use Doctrine\Common\Collections\Collection;
use ReflectionClass;
use ReflectionMethod;

/**
 * Trait that implements the \JsonSerializable Interface.
 *
 * @author  Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
trait JsonSerializableTrait
{

    /**
     * Convert document recursively to array representation.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function jsonSerialize(): array
    {
        $array = [];

        $reflectionClass = new ReflectionClass(\get_class($this));
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $match = [];
            if (preg_match('~^(get|is|has)(.+)~', $method->getName(), $match) === 1) {
                $propertyName = lcfirst($match[2]);

                $propertyValue = $this->{$method->getName()}();

                if ($propertyValue instanceof Collection) {
                    $propertyValue = $propertyValue->toArray();
                }

                $array[$propertyName] = $propertyValue;
            }
        }

        ksort($array);

        return $array;
    }
}
