<?php

namespace Application\Business\Services;

use Application\Business\DomainModels\DomainProduct;
use Application\Business\Interfaces\RepositoryInterfaces\CategoryRepositoryInterface;
use Application\Business\Interfaces\RepositoryInterfaces\ProductRepositoryInterface;
use Application\Business\Interfaces\ServiceInterfaces\ProductServiceInterface;

/**
 * Class ProductService
 *
 * This class implements the ProductServiceInterface interface.
 */
class ProductService implements ProductServiceInterface
{
    /**
     * @var string Error message.
     */
    protected string $lastError;

    /**
     * @param ProductRepositoryInterface $productRepository Repository for product manipulation.
     */
    public function __construct(protected ProductRepositoryInterface $productRepository,
    protected CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * Get total number of products
     *
     * @return int The number of products.
     */
    public function getProductsCount(): int
    {
        return $this->productRepository->getProductsCount();
    }

    /**
     * Get the most viewed product
     *
     * @return DomainProduct The most viewed product.
     */
    public function getMostViewedProduct(): DomainProduct
    {
        return $this->productRepository->getMostViewedProduct();
    }

    /**
     * Get all products as domain models.
     *
     * @return DomainProduct[] An array of domain product models.
     */
    public function getAllProducts(): array
    {
        return $this->productRepository->getAllProducts();
    }

    /**
     * Toggle the enabled state for multiple products.
     *
     * @param array $productIds Array of product IDs.
     * @param bool $isEnabled The new enabled state.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function toggleProductsEnabled(array $productIds, bool $isEnabled): bool
    {
        return $this->productRepository->updateProductsEnabledState($productIds, $isEnabled);
    }

    /**
     * Delete products by their IDs.
     *
     * @param array $productIds Array of product IDs.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function deleteProducts(array $productIds): bool
    {
        $products = $this->productRepository->findDomainProductsByIds($productIds);

        foreach ($products as $product) {
            $imagePath = __DIR__ . '/../../../../resources/' . $product->getImage();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        return $this->productRepository->deleteProducts($productIds);
    }

    /**
     * Creates a new product after validating the provided data.
     *
     * @param array $data Product data.
     * @return bool Indicator of whether the creation was successful.
     */
    public function createProduct(array $data, ?array $imageFile): bool
    {
        // Check if required fields are not empty
        if (empty($data['sku']) || empty($data['title']) || empty($data['brand']) || empty($data['price']) || empty($data['category_id'])) {
            $this->lastError = 'Required fields must not be empty.';
            return false;
        }

        // Validate that the category_id exists
        if (!$this->categoryRepository->findCategoryById($data['category_id'])) {
            $this->lastError = 'The specified category does not exist.';
            return false;
        }

        // Validate that the SKU is unique
        if ($this->productRepository->isSkuTaken($data['sku'])) {
            $this->lastError = 'The SKU must be unique.';
            return false;
        }

        if ($imageFile['name'] !== "") {
            $imageFileName = $this->processImage($imageFile);
            if (!$imageFileName) {
                $this->lastError = 'Image processing failed.';
                return false;
            }
            $data['image'] = $imageFileName;
        }

        $data['enabled'] = filter_var($data['enabled'], FILTER_VALIDATE_BOOLEAN);
        $data['featured'] = filter_var($data['featured'], FILTER_VALIDATE_BOOLEAN);

        return $this->productRepository->createProduct($data);
    }

    /**
     * Processes the uploaded image by validating its dimensions and moving it to the designated directory.
     *
     * This function first checks if the image meets the specified width and aspect ratio requirements.
     * If the image is valid, it generates a unique file name, moves the file to the designated upload directory,
     * and returns the file name. If the image fails validation or the upload process fails, an error message is set,
     * and the function returns null.
     *
     * @param array $imageFile The uploaded image file from the $_FILES array. Expected to contain 'tmp_name' and 'name' keys.
     *
     * @return string|null The unique file name of the uploaded image on success, or null if the image is invalid or the upload fails.
     */
    private function processImage(array $imageFile): ?string
    {
        list($width, $height) = getimagesize($imageFile['tmp_name']);
        if ($width < 600 || ($width / $height) < (4/3) || ($width / $height) > (16/9)) {
            $this->lastError = 'Invalid image dimensions.';
            return null;
        }

        $uploadDir = __DIR__ . '/../../../../resources/';
        $imageFileName = uniqid() . '-' . basename($imageFile['name']);
        $uploadPath = $uploadDir . $imageFileName;

        if (!move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
            $this->lastError = 'Failed to upload image.';
            return null;
        }

        return $imageFileName;
    }

    /**
     * Get paginated products with filtering, sorting, and searching.
     *
     * @param int $page The current page number.
     * @param string $sort The sort direction ('asc' or 'desc').
     * @param int|null $filter The category ID to filter by.
    * @param string|null $search The search term to filter products by title.
     * @return DomainProduct[] The paginated, sorted, and filtered list of products as domain models.
     */
    public function getFilteredAndPaginatedProducts(int $page, string $sort = 'asc', ?int $filter = null, ?string $search = null): array
    {
        return $this->productRepository->getFilteredAndPaginatedProducts($page, $sort, $filter, $search);
    }

    /**
     * Get error message for JSON response
     *
     * @return string Indicator if creating category was successfull.
     */
    public function getLastError(): string
    {
        return $this->lastError ?? '';
    }
}
