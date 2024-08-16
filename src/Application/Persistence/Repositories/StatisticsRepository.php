<?php

namespace Application\Persistence\Repositories;

use Application\Business\Interfaces\RepositoryInterfaces\StatisticsRepositoryInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class StatisticsRepository
 *
 * This class implements the StatisticsRepositoryInterface using SQL database storage.
 */
class StatisticsRepository implements StatisticsRepositoryInterface
{
    /**
     * Get the total number of home page views.
     *
     * @return int The number of home page views.
     */
    public function getHomeViewCount(): int
    {
        return Capsule::table('statistics')->value('home_view_count');
    }
}
