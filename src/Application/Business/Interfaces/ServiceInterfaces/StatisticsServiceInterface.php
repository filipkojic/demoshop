<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

/**
 * Interface StatisticsServiceInterface
 *
 * This interface defines the contract for statistics service.
 */
interface StatisticsServiceInterface
{
    /**
     * Get the total number of home page views.
     *
     * @return int The number of home page views.
     */
    public function getHomeViewCount(): int;
}
