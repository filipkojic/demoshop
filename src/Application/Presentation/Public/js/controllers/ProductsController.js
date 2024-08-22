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

        this.contentDiv = document.getElementById(contentDivId);
        ProductsController.instance = this;

        return this;
    }

    /**
     * Loads and displays all products.
     */
    async loadProducts() {
        try {
            // Fetch products from the backend
            const products = await AjaxService.get('/getAllProducts');

            // Clear the current content
            this.contentDiv.innerHTML = '';

            // Create the table structure
            const table = this.createProductsTable(products);

            // Append the table to the content div
            this.contentDiv.appendChild(table);
        } catch (error) {
            console.error('Error loading products:', error);
            alert('An error occurred while loading the products. Please try again later.');
        }
    }

    /**
     * Creates the HTML table structure for products.
     * @param {array} products - The array of product objects.
     * @returns {HTMLElement} The created HTML table element.
     */
    createProductsTable(products) {
        const table = DomHelper.createElement('table', { class: 'products-table' });

        const headerRow = DomHelper.createElement('tr', { class: 'header-row' });
        const headers = ['Selected', 'Title', 'SKU', 'Brand', 'Category', 'Short description', 'Price', 'Enable', ''];
        headers.forEach(header => {
            const th = DomHelper.createElement('th', {}, header);
            headerRow.appendChild(th);
        });

        table.appendChild(headerRow);

        products.forEach(product => {
            const row = this.createProductRow(product);
            table.appendChild(row);
        });

        return table;
    }

    /**
     * Creates a table row for a single product.
     * @param {object} product - The product object.
     * @returns {HTMLElement} The created HTML table row element.
     */
    createProductRow(product) {
        const row = DomHelper.createElement('tr', { class: 'product-row' });

        const selectCell = DomHelper.createElement('td', {});
        const selectInput = DomHelper.createElement('input', { type: 'checkbox' });
        selectCell.appendChild(selectInput);
        row.appendChild(selectCell);

        const titleCell = DomHelper.createElement('td', {}, product.title);
        row.appendChild(titleCell);

        const skuCell = DomHelper.createElement('td', {}, product.sku);
        row.appendChild(skuCell);

        const brandCell = DomHelper.createElement('td', {}, product.brand);
        row.appendChild(brandCell);

        const categoryCell = DomHelper.createElement('td', {}, product.categoryName);
        row.appendChild(categoryCell);

        const shortDescriptionCell = DomHelper.createElement('td', {}, product.shortDescription);
        row.appendChild(shortDescriptionCell);

        const priceCell = DomHelper.createElement('td', {}, `$${product.price.toFixed(2)}`);
        row.appendChild(priceCell);

        const enableCell = DomHelper.createElement('td', {});
        const enableInput = DomHelper.createElement('input', { type: 'checkbox', checked: product.enabled });
        enableCell.appendChild(enableInput);
        row.appendChild(enableCell);

        const actionCell = DomHelper.createElement('td', { class: 'action-buttons' });
        const editButton = DomHelper.createElement('button', { class: 'edit-button' }, '✏️');
        const deleteButton = DomHelper.createElement('button', { class: 'delete-button' }, '🗑️');
        actionCell.appendChild(editButton);
        actionCell.appendChild(deleteButton);
        row.appendChild(actionCell);

        return row;
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

