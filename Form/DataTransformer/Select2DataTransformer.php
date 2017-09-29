<?php
/**
 *
 * This file is part of the BarcodeBundle
 *
 * @copyright (c) 2017, Electronic Responsible Recyclers
 *
 */

namespace KMJ\ToolkitBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since  1.1
 */
class Select2DataTransformer implements DataTransformerInterface
{

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var string
     */
    private $class;

    /**
     * Should the transformer create an entity if the passed value is not a int
     *
     * @var bool
     */
    private $createTags;

    /**
     * @var string
     */
    private $valueProperty;

    /**
     * Select2DataTransformer constructor.
     *
     * @param EntityManager $manager
     * @param string        $class
     * @param string        $valueProperty
     * @param bool          $createTags
     */
    public function __construct(EntityManager $manager, string $class, string $valueProperty, bool $createTags = false)
    {
        $this->manager = $manager;
        $this->class = $class;
        $this->createTags = $createTags;
        $this->valueProperty = $valueProperty;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        //transform tag into entity;
        /** @var EntityRepository $repo */
        $repo = $this->manager->getRepository($this->class);

        if ($this->createTags && !is_numeric($value)) {
            //create tags is true and the key of the select element selected was not an int.
            //We should create the entity and convert to it

            $entity = new $this->class();

            $reflectionClass = new \ReflectionClass($this->class);

            foreach ($reflectionClass->getProperties() as $property) {
                if ($property->getName() === $this->valueProperty) {
                    $property->setAccessible(true);

                    $property->setValue($entity, $value);

                    $property->setAccessible(false);
                }
            }

            $this->manager->persist($entity);
            $this->manager->flush();
        } else {
            $entity = $repo->find($value);
        }


        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return ($value === null) ? null : $value->getId();
    }
}
