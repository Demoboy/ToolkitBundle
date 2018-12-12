<?php
declare(strict_types = 1);

namespace KMJ\ToolkitBundle\RepositoryFilter;


class DeepLinkedDateTimeBetweenFilter extends DeepLinkedFilter
{

    /**
     * @var DateTimeBetweenFilter|null
     */
    private $dates;

    public function __construct()
    {
        $this->dates = new DateTimeBetweenFilter();
    }

    /**
     * @return DateTimeBetweenFilter|null
     */
    public function getDates(): ?DateTimeBetweenFilter
    {
        return $this->dates;
    }

    /**
     * @param DateTimeBetweenFilter|null $dates
     *
     * @return DeepLinkedDateTimeBetweenFilter
     */
    public function setDates(?DateTimeBetweenFilter $dates): DeepLinkedDateTimeBetweenFilter
    {
        $this->dates = $dates;
        return $this;
    }


}