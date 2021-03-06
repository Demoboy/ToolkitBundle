<?php

declare(strict_types=1);

namespace KMJ\ToolkitBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use KMJ\ToolkitBundle\RepositoryFilter\DateTimeBetweenFilter;
use KMJ\ToolkitBundle\RepositoryFilter\DeepLinkedEntityFilter;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

abstract class FilterableEntityRepository extends EntityRepository
{

    public function filter(array $filter)
    {
        return $this->getFilterQb($filter)->getQuery()->getResult();
    }

    public function getFilterQb(array $filter): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a');

        $resolver = $this->configureFilter();

        /** @var mixed $options */
        $options = $resolver->resolve($filter);

        $reflectionClass = new ReflectionClass($this->getClassName());

        $this->defaultJoins($qb);

        foreach ($options as $key => $option) {
            if (!$option instanceof DeepLinkedEntityFilter) {
                try {
                    $reflectionClass->getProperty($key);
                } catch (ReflectionException $exc) {
                    continue;
                }
            }

            if (is_array($option) || $option instanceof Traversable) {
                if (count($option) === 0) {
                    continue;
                }

                $this->filterArray($qb, $key, $option);
            } elseif ($option instanceof DateTimeBetweenFilter) {
                $this->filterDateTime($qb, $key, $option);
            } elseif ($option instanceof DeepLinkedEntityFilter) {
                $this->filterDeepLinkedEntity($qb, $option);
            } elseif ($option !== null) {
                $this->filterPlain($qb, $key, $option);
            }
        }

        $qb->orderBy('a.id', 'DESC');

        return $qb;
    }
    private function filterPlain(QueryBuilder $qb, $property, $value) {
        $qb->andWhere($qb->expr()->eq('a.'.$property, ':option_'.$property));
        $qb->setParameter('option_'.$property, $value);
    }

    abstract protected function configureFilter(): OptionsResolver;

    protected function defaultJoins(QueryBuilder $qb)
    {
        return null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $property
     * @param              $array
     */
    private function filterArray(QueryBuilder $qb, string $property, $array)
    {
        $inArray = [];
        foreach ($array as $option) {
            if (is_object($option)) {
                if (method_exists($option, 'getId')) {
                    $inArray[] = $option->getId();
                } else {
                    $inArray[] = (string)$option;
                }
            } else {
                $inArray[] = $option;
            }
        }

        $qb->andWhere($qb->expr()->in('a.'.$property, $inArray));
    }

    /**
     * Handles filtering a DateTimeBetweenFilter to insert a query statement for the provided property
     *
     * @param QueryBuilder          $qb
     * @param string                $property
     * @param DateTimeBetweenFilter $option
     */
    private function filterDateTime(QueryBuilder $qb, string $property, DateTimeBetweenFilter $option)
    {
        if ($option->getStart() !== null && $option->getEnd() !== null) {
            $qb->andWhere($qb->expr()->between('a.'.$property, ':start_date_'.$property, ':end_date_'.$property));
            $qb->setParameter('start_date_'.$property, $option->getStart()->format('Y-m-d'));
            $qb->setParameter('end_date_'.$property, $option->getEnd()->format('Y-m-d'));
        } elseif ($option->getStart() !== null && $option->getEnd() === null
        ) {  //start date is not empty but end date is
            $qb->andWhere($qb->expr()->gte('a.'.$property, ":start_date_".$property));
            $qb->setParameter('start_date_'.$property, $option->getStart()->format('Y-m-d'));
        } elseif ($option->getStart() === null && $option->getEnd() !== null) {
            $qb->andWhere($qb->expr()->lte('a.'.$property, ":end_date_".$property));
            $qb->setParameter('end_date_'.$property, $option->getEnd()->format('Y-m-d'));
        }
    }

    private function filterDeepLinkedEntity(QueryBuilder $qb, DeepLinkedEntityFilter $option)
    {
        $option->getMappingQbCallback()($qb);
        $inArray = [];
        $entity = $option->getEntity();


        if ((is_array($entity) || $entity instanceof Traversable) && count($entity) !== 0) {
            foreach ($entity as $opt) {
                if (is_object($opt)) {
                    if (method_exists($opt, "getId")) {
                        $inArray[] = $opt->getId();
                    } else {
                        $inArray[] = (string)$opt;
                    }
                } else {
                    $inArray[] = $opt;
                }
            }
        } else {
            if (is_object($option->getEntity()) && (method_exists($option->getEntity(), "getId"))) {
                $inArray[] = $option->getEntity()->getId();
            } else {
                $inArray[] = $option->getEntity();
            }
        }

        $qb->andWhere($qb->expr()->in($option->getTableAlias().'.id', $inArray));
    }
}