<?php

declare(strict_types=1);

namespace KMJ\ToolkitBundle\Repository;


use Doctrine\ORM\EntityRepository;
use PhpOption\Option;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

abstract class FilterableEntityRepository extends EntityRepository {

    public function filter(array $filter) {
        $qb = $this->createQueryBuilder("a");

        $resolver = $this->configureFilter();
        $options = $resolver->resolve($filter);

        $reflectionClass = new ReflectionClass($this->getClassName());

        foreach ($options as $key => $option) {
            try {
                $reflectionClass->getProperty($key);
            } catch(ReflectionException $exc) {
                continue;
            }

            if ((is_array($option) || $option instanceof Traversable) && count($option) !== 0) {
                $inArray = [];
                foreach ($option as $opt) {
                    if (is_object($opt)) {
                        if (method_exists($opt, "getId")) {
                            $inArray[] = $opt->getId();
                        } else {
                            $inArray[] = (string) $opt;
                        }
                    } else {
                        $inArray[] = $opt;
                    }

                }
                $qb->andWhere($qb->expr()->in("a.{$key}", $inArray));
            }
        }

        return $qb->getQuery()->getResult();
    }

    abstract protected function configureFilter(): OptionsResolver;
}