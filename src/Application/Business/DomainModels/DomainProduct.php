<?php

namespace Application\Business\DomainModels;

/**
 * Class DomainProduct
 *
 * Represents a product in the domain model, including details such as category, SKU, title, brand, price, and more.
 */
class DomainProduct
{
    /**
     * @var DomainCategory|null $category The category to which this product belongs.
     */
    private ?DomainCategory $category = null;

    /**
     * DomainProduct constructor.
     *
     * @param int $id The unique identifier for the product.
     * @param int $categoryId The ID of the category to which this product belongs.
     * @param string $sku The stock keeping unit (SKU) for the product.
     * @param string $title The title of the product.
     * @param string $brand The brand of the product.
     * @param float $price The price of the product.
     * @param string|null $shortDescription An optional short description of the product.
     * @param string|null $description An optional detailed description of the product.
     * @param string|null $image An optional image URL for the product.
     * @param bool $enabled Indicates if the product is enabled (visible to customers).
     * @param bool $featured Indicates if the product is featured on the website.
     * @param int $viewCount The number of times the product has been viewed.
     */
    public function __construct(
        private int     $id,
        private int     $categoryId,
        private string  $sku,
        private string  $title,
        private string  $brand,
        private float   $price,
        private ?string $shortDescription = null,
        private ?string $description = null,
        private ?string $image = null,
        private bool    $enabled = true,
        private bool    $featured = false,
        private int     $viewCount = 0
    )
    {
    }

    /**
     * Get the unique identifier for the product.
     *
     * @return int The ID of the product.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the ID of the category to which this product belongs.
     *
     * @return int The ID of the category.
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * Get the stock keeping unit (SKU) for the product.
     *
     * @return string The SKU of the product.
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Get the title of the product.
     *
     * @return string The title of the product.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the brand of the product.
     *
     * @return string The brand of the product.
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * Get the price of the product.
     *
     * @return float The price of the product.
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Get the short description of the product.
     *
     * @return string|null The short description of the product, or null if not set.
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * Get the detailed description of the product.
     *
     * @return string|null The description of the product, or null if not set.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the image URL for the product.
     *
     * @return string|null The image URL of the product, or null if not set.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Check if the product is enabled (visible to customers).
     *
     * @return bool True if the product is enabled, false otherwise.
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Check if the product is featured on the website.
     *
     * @return bool True if the product is featured, false otherwise.
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * Get the number of times the product has been viewed.
     *
     * @return int The view count of the product.
     */
    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    /**
     * Get the category to which this product belongs.
     *
     * @return DomainCategory|null The category of the product, or null if not set.
     */
    public function getCategory(): ?DomainCategory
    {
        return $this->category;
    }

    /**
     * Set the category for this product.
     *
     * @param DomainCategory $category The category to set.
     */
    public function setCategory(DomainCategory $category): void
    {
        $this->category = $category;
    }
}
