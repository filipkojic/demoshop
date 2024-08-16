<?php

namespace Application\Business\Interfaces\ServiceInterfaces;

interface CategoryServiceInterface
{
    /**
     * Get total number of categories
     *
     * @return int The number of categories.
     */
    public function getCategoriesCount(): int;
}