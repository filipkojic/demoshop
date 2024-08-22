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
     * Creates a button panel with action buttons.
     * @returns {HTMLElement} The created button panel element.
     */
    createButtonPanel() {
        const panel = DomHelper.createElement('div', { class: 'button-panel' });

        const addButton = DomHelper.createElement('button', { class: 'add-button action-button' }, 'Add new product');
        const deleteButton = DomHelper.createElement('button', { class: 'delete-button action-button' }, 'Delete selected');
        const enableButton = DomHelper.createElement('button', { class: 'enable-button action-button' }, 'Enable selected');
        const disableButton = DomHelper.createElement('button', { class: 'disable-button action-button' }, 'Disable selected');
        const filterButton = DomHelper.createElement('button', { class: 'filter-button action-button' }, 'Filter');

        enableButton.addEventListener('click', () => this.enableSelectedProducts());
        disableButton.addEventListener('click', () => this.disableSelectedProducts());
        deleteButton.addEventListener('click', () => this.handleDeleteProducts());

        panel.appendChild(addButton);
        panel.appendChild(deleteButton);
        panel.appendChild(enableButton);
        panel.appendChild(disableButton);
        panel.appendChild(filterButton);

        return panel;
    }

    /**
     * Creates a table row for a single product.
     * @param {object} product - The product object.
     * @returns {HTMLElement} The created HTML table row element.
     */
    createProductRow(product) {
        const row = DomHelper.createElement('tr', { class: 'product-row', 'data-id': product.id });

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
        const enableInput = DomHelper.createElement('input', { type: 'checkbox' });

        enableInput.checked = product.enabled;
        enableInput.disabled = true;

        enableCell.appendChild(enableInput);
        row.appendChild(enableCell);

        const actionCell = DomHelper.createElement('td', { class: 'action-buttons' });
        const editButton = DomHelper.createElement('button', { class: 'edit-button' }, '✏️');
        const deleteButton = DomHelper.createElement('button', { class: 'delete-button' }, '🗑️');

        editButton.addEventListener('click', () => this.handleEditProduct(product.id));
        deleteButton.addEventListener('click', () => this.handleDeleteProducts([product.id]));

        actionCell.appendChild(editButton);
        actionCell.appendChild(deleteButton);
        row.appendChild(actionCell);

        return row;
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
     * Loads and displays all products.
     */
    async loadProducts() {
        try {
            const products = await AjaxService.get('/getAllProducts');
            this.contentDiv.innerHTML = ''; //

            const buttonPanel = this.createButtonPanel();

            const searchRow = DomHelper.createElement('div', { class: 'search-row' });

            const searchInput = DomHelper.createElement('input', {
                type: 'text',
                class: 'search-input',
                placeholder: 'Search by title...'
            });

            searchInput.addEventListener('input', (e) => this.filterProductsByTitle(e.target.value));

            searchRow.appendChild(searchInput);

            const table = this.createProductsTable(products);

            this.contentDiv.appendChild(buttonPanel);
            this.contentDiv.appendChild(searchRow);
            this.contentDiv.appendChild(table);
        } catch (error) {
            console.error('Error loading products:', error);
            alert('An error occurred while loading the products. Please try again later.');
        }
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

    /**
     * Gets all selected product IDs.
     * Iterates through the product rows and collects the IDs of those with selected checkboxes.
     *
     * @returns {Array<string>} An array of selected product IDs.
     */
    getSelectedProductIds() {
        const selectedProductIds = [];
        const rows = this.contentDiv.querySelectorAll('.product-row');

        rows.forEach(row => {
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (checkbox.checked) {
                selectedProductIds.push(row.getAttribute('data-id'));
            }
        });

        return selectedProductIds;
    }

    // Handle methods

    /**
     * Enables all selected products.
     */
    async enableSelectedProducts() {
        const selectedProductIds = this.getSelectedProductIds();
        if (selectedProductIds.length === 0) {
            alert('Please select at least one product to enable.');
            return;
        }

        await this.toggleProductsEnabledState(selectedProductIds, true);
    }

    /**
     * Disables all selected products.
     */
    async disableSelectedProducts() {
        const selectedProductIds = this.getSelectedProductIds();
        if (selectedProductIds.length === 0) {
            alert('Please select at least one product to disable.');
            return;
        }

        await this.toggleProductsEnabledState(selectedProductIds, false);
    }

    /**
     * Toggles the enabled state of multiple products.
     * @param {array} productIds - The array of product IDs.
     * @param {boolean} isEnabled - The new enabled state.
     */
    async toggleProductsEnabledState(productIds, isEnabled) {
        try {
            // Send a request to update the products' enabled state in the backend
            const response = await AjaxService.patch('/toggleProductsEnabled', JSON.stringify({ productIds, isEnabled }));

            if (response.success) {
                await this.loadProducts();
            } else {
                alert('Failed to update product state. Please try again later.');
            }
        } catch (error) {
            console.error('Error updating products enabled state:', error);
            alert('An error occurred while updating the products. Please try again later.');
        }
    }


    /**
     * Handles the edit product action.
     * Triggered when the edit button for a product is clicked.
     *
     * @param {number} productId - The ID of the product to be edited.
     */
    handleEditProduct(productId) {
        alert(`Editing product with ID: ${productId}`);
    }

    /**
     * Deletes the selected products or a specific product if IDs are provided.
     * @param {Array<number>} [productIds] - An optional array of product IDs to delete.
     */
    async handleDeleteProducts(productIds = null) {
        const idsToDelete = productIds || this.getSelectedProductIds();

        if (idsToDelete.length === 0) {
            alert('Please select at least one product to delete.');
            return;
        }

        const confirmation = confirm('Are you sure you want to delete the selected products? This action cannot be undone.');

        if (!confirmation) {
            return;
        }

        try {
            const response = await AjaxService.delete('/deleteProducts', JSON.stringify({ productIds: idsToDelete }));

            if (response.success) {
                alert('Products deleted successfully.');
                await this.loadProducts(); // Reload the products table after deletion
            } else {
                alert('Failed to delete products. Please try again later.');
            }
        } catch (error) {
            console.error('Error deleting products:', error);
            alert('An error occurred while deleting the products. Please try again later.');
        }
    }

    /**
     * Filters products based on the search query in the title field.
     * @param {string} query - The search query entered by the user.
     */
    filterProductsByTitle(query) {
        const rows = this.contentDiv.querySelectorAll('.product-row');
        query = query.toLowerCase();

        rows.forEach(row => {
            const titleCell = row.querySelector('td:nth-child(2)');
            const titleText = titleCell.textContent.toLowerCase();

            if (titleText.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

}
