/**
 * ProductsController class to manage the content of the "Products" page.
 * Implements the Singleton pattern to ensure only one instance is used across the application.
 */
class ProductsController {
    /**
     * Creates an instance of ProductsController or returns the existing instance.
     * @param {string} contentDivId - The ID of the div where the content will be rendered.
     * @returns {ProductsController} The single instance of ProductsController.
     */
    constructor(contentDivId) {
        if (ProductsController.instance) {
            return ProductsController.instance;
        }

        /**
         * @property {HTMLElement} contentDiv - The div element where the content will be rendered.
         */
        this.contentDiv = document.getElementById(contentDivId);

        // Store the instance
        ProductsController.instance = this;

        return this;
    }

    /**
     * Loads content for the "Products" page.
     * Clears any existing content in the contentDiv and appends new elements.
     */
    loadProducts() {
        this.contentDiv.innerHTML = ''; // Clear existing content
        const productsHeading = DomHelper.createElement('h2', {}, 'Products Section'); // Create heading
        const noDataMessage = DomHelper.createElement('p', {}, 'No data available.'); // Create no data message
        this.contentDiv.appendChild(productsHeading); // Append heading to content div
        this.contentDiv.appendChild(noDataMessage); // Append no data message to content div
    }

    /**
     * Retrieves the single instance of ProductsController.
     * If the instance does not exist, it creates it using the provided contentDivId.
     * @returns {ProductsController} The single instance of ProductsController.
     */
    static getInstance() {
        if (!ProductsController.instance) {
            ProductsController.instance = new ProductsController('content');
        }
        return ProductsController.instance;
    }
}
