<?php

declare(strict_types = 1);

namespace KMJ\ToolkitBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use KMJ\ToolkitBundle\RepositoryFilter\DateTimeBetweenFilter;
use KMJ\ToolkitBundle\RepositoryFilter\DeepLinkedDateTimeBetweenFilter;
use KMJ\ToolkitBundle\RepositoryFilter\DeepLinkedEntityFilter;
use KMJ\ToolkitBundle\RepositoryFilter\DeepLinkedFilter;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

abstract class FilterableEntityRepository extends EntityRepository
{

//<editor-fold desc="Getters and Setters">
    public function getFilterQb(array $filter): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a');

        $resolver = $this->configureFilter();

        /** @var mixed $options */
        $options = $resolver->resolve($filter);

        $reflectionClass = new ReflectionClass($this->getClassName());

        $this->defaultJoins($qb);

        foreach ($options as $key => $option) {
            if (!$option instanceof DeepLinkedFilter) {
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
            } elseif ($option instanceof DeepLinkedDateTimeBetweenFilter) {
                $this->filterDeepLinkedDateTimeBetween($qb, $key, $option);
            } elseif ($option !== null) {
                $this->filterPlain($qb, $key, $option);
            }
        }

        $this->addFilterOrderBy($qb);

        $maxResults = $this->maxResults();

        if ($maxResults !== null) {
            $qb->setMaxResults($maxResults);
        }

        return $qb;
    }

//</editor-fold>

    abstract public function configureFilter(): OptionsResolver;

    public function filter(array $filter)
    {
        return $this->getFilterQb($filter)->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $qb
     * @param              $option
     */
    public function filterDeepLinkedDateTimeBetween(
        QueryBuilder $qb,
        string $property,
        DeepLinkedDateTimeBetweenFilter $option
    ): void {
        $option->getMappingQbCallback()($qb);
        $this->filterDateTime($qb, $property, $option->getDates(), $option->getTableAlias());
    }

    protected function defaultJoins(QueryBuilder $qb)
    {
        return null;
    }

    protected function maxResults()
    {
        return null;
    }

    protected function orderByFields()
    {
        return [];
    }

    private function addFilterOrderBy(QueryBuilder $qb)
    {
        $orderByFields = $this->orderByFields();

        if (\count($orderByFields) !== 0) {
            foreach ($orderByFields as $field => $order) {
                $qb->orderBy('a.'.$field, $order);
            }
        }
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
                    $inArray[] = (string) $option;
                }
            } else {
                $inArray[] = $option;
            }
        }

        $paramKey = $this->parameterKey();

        $qb->andWhere($qb->expr()->in('a.'.$property, ":array_{$paramKey}"))
            ->setParameter("array_{$paramKey}", $inArray);
    }

    /**
     * Handles filtering a DateTimeBetweenFilter to insert a query statement for the provided property
     *
     * @param QueryBuilder          $qb
     * @param string                $property
     * @param DateTimeBetweenFilter $option
     * @param string                $alias
     */
    private function filterDateTime(QueryBuilder $qb, string $property, DateTimeBetweenFilter $option, $alias = 'a')
    {
        if ($option->getStart() !== null && $option->getEnd() !== null) {
            $qb->andWhere($qb->expr()->between($alias.'.'.$property, ':start_date_'.$property, ':end_date_'.$property));
            $qb->setParameter('start_date_'.$property, $option->getStart()->format('Y-m-d'));
            $qb->setParameter('end_date_'.$property, $option->getEnd()->format('Y-m-d'));
        } elseif ($option->getStart() !== null && $option->getEnd() === null
        ) {  //start date is not empty but end date is
            $qb->andWhere($qb->expr()->gte($alias.'.'.$property, ":start_date_".$property));
            $qb->setParameter('start_date_'.$property, $option->getStart()->format('Y-m-d'));
        } elseif ($option->getStart() === null && $option->getEnd() !== null) {
            $qb->andWhere($qb->expr()->lte($alias.'.'.$property, ":end_date_".$property));
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
                        $inArray[] = (string) $opt;
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

        $paramKey = $this->parameterKey();

        $qb->andWhere($qb->expr()->in($option->getTableAlias().'.id', ":de_array_{$paramKey}"))
            ->setParameter("de_array_{$paramKey}", $inArray);
    }

    private function filterPlain(QueryBuilder $qb, $property, $value)
    {
        $qb->andWhere($qb->expr()->eq('a.'.$property, ':option_'.$property));
        $qb->setParameter('option_'.$property, $value);
    }

    private function parameterKey()
    {
        return substr(md5(microtime()), 0, 5);
    }
}