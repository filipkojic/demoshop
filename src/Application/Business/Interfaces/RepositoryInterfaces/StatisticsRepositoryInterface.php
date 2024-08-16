<?php

namespace Application\Business\Interfaces\RepositoryInterfaces;

/**
 * Interface StatisticsRepositoryInterface
 *
 * This interface defines the contract for the statistics repository.
 */
interface StatisticsRepositoryInterface
{
    /**
     * Get the total number of home page views.
     *
     * @return int The number of home page views.
     */
    public function getHomeViewCount(): int;
}

