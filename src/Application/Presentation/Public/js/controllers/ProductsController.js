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
        const enableInput = DomHelper.createElement('input', { type: 'checkbox', checked: product.enabled });
        enableCell.appendChild(enableInput);
        row.appendChild(enableCell);

        const actionCell = DomHelper.createElement('td', { class: 'action-buttons' });
        const editButton = DomHelper.createElement('button', { class: 'edit-button' }, '✏️');
        const deleteButton = DomHelper.createElement('button', { class: 'delete-button' }, '🗑️');

        // Adding event listeners for edit and delete actions
        editButton.addEventListener('click', () => this.handleEditProduct(product.id));
        deleteButton.addEventListener('click', () => this.handleDeleteProduct(product.id));

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
            this.contentDiv.innerHTML = '';

            const buttonPanel = this.createButtonPanel();
            const table = this.createProductsTable(products);

            this.contentDiv.appendChild(buttonPanel);
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

    /**
     * Handles the edit product action.
     * Triggered when the edit button for a product is clicked.
     *
     * @param {number} productId - The ID of the product to be edited.
     */
    handleEditProduct(productId) {
        // Your logic to edit the product with this productId
        alert(`Editing product with ID: ${productId}`);
        // Implement edit logic here...
        //alert(this.getSelectedProductIds());
    }

    /**
     * Handles the delete product action.
     * Triggered when the delete button for a product is clicked.
     *
     * @param {number} productId - The ID of the product to be deleted.
     */
    handleDeleteProduct(productId) {
        // Your logic to delete the product with this productId
        alert(`Deleting product with ID: ${productId}`);
        // Implement delete logic here, such as confirming the deletion and then calling a backend service
        //alert(this.getSelectedProductIds());
    }
}
