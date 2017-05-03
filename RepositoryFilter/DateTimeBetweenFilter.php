<?php
/**
 *
 * This file is part of the BarcodeBundle
 *
 * @copyright (c) 2017, Electronic Responsible Recyclers
 *
 */

namespace KMJ\ToolkitBundle\RepositoryFilter;

use DateTime;

/**
 * Created by IntelliJ IDEA.
 * User: kaelin
 * Date: 4/26/17
 * Time: 11:47 AM
 */
class DateTimeBetweenFilter
{

    /**
     * @var DateTime|null
     */
    private $start;

    /**
     * @var DateTime|null
     */
    private $end;

    /**
     * @return DateTime|null
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param DateTime|null $start
     *
     * @return DateTimeBetweenFilter
     */
    public function setStart($start): DateTimeBetweenFilter
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param DateTime|null $end
     *
     * @return DateTimeBetweenFilter
     */
    public function setEnd($end): DateTimeBetweenFilter
    {
        $this->end = $end;

        return $this;
    }


}