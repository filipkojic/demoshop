<?php

namespace Application\Business\DomainModels;

/**
 * Class DomainCategory
 *
 * Represents a product category in the domain model, including its subcategories and products.
 */
class DomainCategory
{
    /**
     * @var DomainCategory[] $subcategories Array of subcategories under this category.
     */
    private array $subcategories = [];

    /**
     * @var DomainProduct[] $products Array of products under this category.
     */
    private array $products = [];

    /**
     * DomainCategory constructor.
     *
     * @param int $id The unique identifier for the category.
     * @param int|null $parentId The ID of the parent category, or null if this is a root category.
     * @param string $code The unique code for the category.
     * @param string $title The title of the category.
     * @param string|null $description Optional description of the category.
     */
    public function __construct(
        private int     $id,
        private ?int    $parentId,
        private string  $code,
        private string  $title,
        private ?string $description = null
    ) {
    }

    /**
     * Get the unique identifier for the category.
     *
     * @return int The ID of the category.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the ID of the parent category, or null if this is a root category.
     *
     * @return int|null The ID of the parent category, or null.
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * Get the unique code for the category.
     *
     * @return string The code of the category.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the title of the category.
     *
     * @return string The title of the category.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the description of the category.
     *
     * @return string|null The description of the category, or null if not set.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the subcategories under this category.
     *
     * @return DomainCategory[] An array of subcategories.
     */
    public function getSubcategories(): array
    {
        return $this->subcategories;
    }

    /**
     * Add a subcategory to this category.
     *
     * @param DomainCategory $subcategory The subcategory to add.
     */
    public function addSubcategory(DomainCategory $subcategory): void
    {
        $this->subcategories[] = $subcategory;
    }

    /**
     * Get the products under this category.
     *
     * @return DomainProduct[] An array of products.
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Add a product to this category.
     *
     * @param DomainProduct $product The product to add.
     */
    public function addProduct(DomainProduct $product): void
    {
        $this->products[] = $product;
    }
}
