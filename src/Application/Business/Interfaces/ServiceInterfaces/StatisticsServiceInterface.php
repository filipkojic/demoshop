<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

interface StatisticsServiceInterface
{
    /**
     * Get the total number of home page views.
     *
     * @return int The number of home page views.
     */
    public function getHomeViewCount(): int;
}
