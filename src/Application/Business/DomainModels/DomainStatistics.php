<?php

namespace Application\Business\DomainModels;

/**
 * Class DomainStatistics
 *
 * Represents the statistics entity in the domain model, specifically tracking the view count for the homepage.
 */
class DomainStatistics
{
    /**
     * DomainStatistics constructor.
     *
     * @param int $id The unique identifier for the statistics entry.
     * @param int $homeViewCount The count of views on the homepage.
     */
    public function __construct(
        private int $id,
        private int $homeViewCount
    )
    {
    }

    /**
     * Get the unique identifier for the statistics entry.
     *
     * @return int The ID of the statistics entry.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the count of views on the homepage.
     *
     * @return int The number of homepage views.
     */
    public function getHomeViewCount(): int
    {
        return $this->homeViewCount;
    }

    /**
     * Increment the count of views on the homepage by one.
     */
    public function incrementHomeViewCount(): void
    {
        $this->homeViewCount++;
    }
}
