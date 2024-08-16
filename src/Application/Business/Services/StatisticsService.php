<?php

namespace Application\Business\Services;

use Application\Business\Interfaces\RepositoryInterfaces\StatisticsRepositoryInterface;
use Application\Business\Interfaces\ServiceInterfaces\StatisticsServiceInterface;

/**
 * Class StatisticsService
 *
 * This class implements the StatisticsServiceInterface interface.
 */
class StatisticsService implements StatisticsServiceInterface
{
    public function __construct(protected StatisticsRepositoryInterface $statisticsRepository)
    {
    }

    /**
     * Get the total number of home page views.
     *
     * @return int The number of home page views.
     */
    public function getHomeViewCount(): int
    {
        return $this->statisticsRepository->getHomeViewCount();
    }
}
