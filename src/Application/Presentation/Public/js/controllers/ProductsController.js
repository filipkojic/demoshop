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

        this.page = 1;
        this.sortOrder = 'asc';
        this.categoryId = '';
        this.searchQuery = '';

        this.debounceTimeout = null;

        ProductsController.instance = this;

        return this;
    }

    /**
     * Creates a button panel with action buttons.
     * @returns {HTMLElement} The created button panel element.
     */
    createButtonPanel(categories) {
        const panel = DomHelper.createElement('div', { class: 'button-panel' });

        const addButton = DomHelper.createElement('button', { class: 'add-button action-button' }, 'Add new product');
        const deleteButton = DomHelper.createElement('button', { class: 'delete-button action-button' }, 'Delete selected');
        const enableButton = DomHelper.createElement('button', { class: 'enable-button action-button' }, 'Enable selected');
        const disableButton = DomHelper.createElement('button', { class: 'disable-button action-button' }, 'Disable selected');

        enableButton.addEventListener('click', () => this.enableSelectedProducts());
        disableButton.addEventListener('click', () => this.disableSelectedProducts());
        deleteButton.addEventListener('click', () => this.handleDeleteProducts());

        const categorySelect = DomHelper.createElement('select', { class: 'category-select' });

        const defaultOption = DomHelper.createElement('option', { value: '' }, 'All Categories');
        categorySelect.appendChild(defaultOption);

        const addCategoryOptions = (categories, level = 0) => {
            categories.forEach(category => {
                const optionText = `${'—'.repeat(level)} ${category.title}`;
                const option = DomHelper.createElement('option', { value: category.id }, optionText);
                categorySelect.appendChild(option);

                if (category.subcategories && category.subcategories.length > 0) {
                    addCategoryOptions(category.subcategories, level + 1);
                }
            });
        };

        addCategoryOptions(categories);

        // Set the selected category from the current filter
        categorySelect.value = this.categoryId;

        categorySelect.addEventListener('change', (e) => {
            this.categoryId = e.target.value;
            this.page = 1;
            this.loadProducts();
        });

        const sortSelect = DomHelper.createElement('select', { class: 'sort-select' });
        const ascOption = DomHelper.createElement('option', { value: 'asc' }, 'Price: Low to High');
        const descOption = DomHelper.createElement('option', { value: 'desc' }, 'Price: High to Low');
        sortSelect.appendChild(ascOption);
        sortSelect.appendChild(descOption);

        sortSelect.addEventListener('change', (e) => {
            this.sortOrder = e.target.value;
            this.page = 1;
            this.loadProducts();
        });

        // Set the selected sort order from the current filter
        sortSelect.value = this.sortOrder;

        panel.appendChild(addButton);
        panel.appendChild(deleteButton);
        panel.appendChild(enableButton);
        panel.appendChild(disableButton);
        panel.appendChild(categorySelect);
        panel.appendChild(sortSelect);

        return panel;
    }


    /**
     * Creates a table row for a single product.
     * @param {object} product - The product object.
     * @returns {HTMLElement} The created HTML table row element.
     */
    createProductRow(product) {
        const row = DomHelper.createElement('tr', {
            class: 'product-row',
            'data-id': product.id,
            'data-category-id': product.categoryId
        });

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

    createPaginationControls(totalPages) {
        const paginationRow = DomHelper.createElement('div', { class: 'pagination-row' });

        const firstButton = DomHelper.createElement('button', {}, '<<');
        const prevButton = DomHelper.createElement('button', {}, '<');
        const nextButton = DomHelper.createElement('button', {}, '>');
        const lastButton = DomHelper.createElement('button', {}, '>>');

        firstButton.addEventListener('click', () => {
            if (this.page > 1) {
                this.page = 1;
                this.loadProducts();
            }
        });

        prevButton.addEventListener('click', () => {
            if (this.page > 1) {
                this.page--;
                this.loadProducts();
            }
        });

        nextButton.addEventListener('click', () => {
            if (this.page < totalPages) {
                this.page++;
                this.loadProducts();
            }
        });

        lastButton.addEventListener('click', () => {
            if (this.page < totalPages) {
                this.page = totalPages;
                this.loadProducts();
            }
        });

        paginationRow.appendChild(firstButton);
        paginationRow.appendChild(prevButton);
        paginationRow.appendChild(DomHelper.createElement('span', {}, `${this.page} / ${totalPages}`));
        paginationRow.appendChild(nextButton);
        paginationRow.appendChild(lastButton);

        return paginationRow;
    }

    /**
     * Loads and displays products with the current filters (pagination, search, category).
     */
    async loadProducts() {
        try {
            const params = new URLSearchParams({
                page: this.page,
                sort: this.sortOrder,
                filter: this.categoryId || '',
                search: this.searchQuery || ''
            });

            const response = await AjaxService.get(`/getFilteredAndPaginatedProducts?${params.toString()}`);
            const { products, total, per_page } = response;

            this.contentDiv.innerHTML = '';

            const categories = await AjaxService.get('/getCategories');
            const buttonPanel = this.createButtonPanel(categories);
            const searchRow = DomHelper.createElement('div', { class: 'search-row' });

            const searchInput = DomHelper.createElement('input', {
                type: 'text',
                class: 'search-input',
                placeholder: 'Search by title...',
                value: this.searchQuery // Setuje trenutnu vrednost pretrage
            });

            searchInput.addEventListener('input', (e) => {
                clearTimeout(this.debounceTimeout);
                this.searchQuery = e.target.value;

                this.debounceTimeout = setTimeout(() => {
                    this.page = 1;
                    this.loadProducts();
                }, 300);
            });

            searchRow.appendChild(searchInput);

            const table = this.createProductsTable(products);
            const totalPages = Math.ceil(total / per_page);
            const paginationControls = this.createPaginationControls(totalPages);

            this.contentDiv.appendChild(buttonPanel);
            this.contentDiv.appendChild(searchRow);
            this.contentDiv.appendChild(table);
            this.contentDiv.appendChild(paginationControls);

            buttonPanel.querySelector('.add-button').addEventListener('click', () => {
                this.displayAddProductForm(categories);
            });

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
     * Renders the form for adding a new product.
     * @param {Array} categories - The array of category objects used to populate the category select dropdown.
     */
    displayAddProductForm(categories) {
        this.contentDiv.innerHTML = '';

        const form = DomHelper.createElement('form', { id: 'add-product-form' });

        form.appendChild(this.createFormInput('SKU', 'sku'));
        form.appendChild(this.createFormInput('Title', 'title'));
        form.appendChild(this.createFormInput('Brand', 'brand'));
        form.appendChild(this.createFormSelect('Category', 'category_id', categories));
        form.appendChild(this.createFormInput('Price', 'price', 'number'));
        form.appendChild(this.createFormTextArea('Short description', 'short_description'));
        form.appendChild(this.createFormTextArea('Description', 'description'));

        form.appendChild(this.createFormCheckbox('Enabled in shop', 'enabled'));
        form.appendChild(this.createFormCheckbox('Featured', 'featured'));

        const imageDiv = DomHelper.createElement('div');
        const imageLabel = DomHelper.createElement('label', {}, 'Image:');
        const imageInput = DomHelper.createElement('input', { type: 'file', id: 'image', name: 'image' });
        imageDiv.appendChild(imageLabel);
        imageDiv.appendChild(imageInput);
        form.appendChild(imageDiv);

        const submitButton = DomHelper.createElement('button', { type: 'submit' }, 'Save');
        form.appendChild(submitButton);

        this.contentDiv.appendChild(form);

        form.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    /**
     * Creates a standard input field.
     * @param {string} labelText - The label text for the input field.
     * @param {string} name - The name attribute for the input field.
     * @param {string} [type='text'] - The type attribute for the input field.
     * @returns {HTMLElement} The created input field wrapped in a div element.
     */
    createFormInput(labelText, name, type = 'text') {
        const div = DomHelper.createElement('div');
        const label = DomHelper.createElement('label', {}, labelText);
        const input = DomHelper.createElement('input', { type, name });
        div.appendChild(label);
        div.appendChild(input);
        return div;
    }

    /**
     * Creates a select dropdown field for selecting a category.
     * @param {string} labelText - The label text for the select field.
     * @param {string} name - The name attribute for the select field.
     * @param {Array} categories - The array of category objects to populate the select options.
     * @returns {HTMLElement} The created select field wrapped in a div element.
     */
    createFormSelect(labelText, name, categories) {
        const div = DomHelper.createElement('div');
        const label = DomHelper.createElement('label', {}, labelText);
        const select = DomHelper.createElement('select', { name });

        const addCategoryOptions = (categories, level = 0) => {
            categories.forEach(category => {
                const optionText = `${'—'.repeat(level)} ${category.title}`;
                const option = DomHelper.createElement('option', { value: category.id }, optionText);
                select.appendChild(option);

                if (category.subcategories && category.subcategories.length > 0) {
                    addCategoryOptions(category.subcategories, level + 1);
                }
            });
        };

        addCategoryOptions(categories);

        div.appendChild(label);
        div.appendChild(select);
        return div;
    }

    /**
     * Creates a textarea field for multi-line text input.
     * @param {string} labelText - The label text for the textarea field.
     * @param {string} name - The name attribute for the textarea field.
     * @returns {HTMLElement} The created textarea field wrapped in a div element.
     */
    createFormTextArea(labelText, name) {
        const div = DomHelper.createElement('div');
        const label = DomHelper.createElement('label', {}, labelText);
        const textarea = DomHelper.createElement('textarea', { name });
        div.appendChild(label);
        div.appendChild(textarea);
        return div;
    }

    /**
     * Creates a checkbox field.
     * @param {string} labelText - The label text for the checkbox field.
     * @param {string} name - The name attribute for the checkbox field.
     * @returns {HTMLElement} The created checkbox field wrapped in a div element.
     */
    createFormCheckbox(labelText, name) {
        const div = DomHelper.createElement('div');
        const label = DomHelper.createElement('label', {}, labelText);
        const checkbox = DomHelper.createElement('input', { type: 'checkbox', name });
        div.appendChild(label);
        div.appendChild(checkbox);
        return div;
    }

    /**
     * Handles the form submission for adding a new product.
     * @param {Event} e - The submit event.
     */
    async handleFormSubmit(e) {
        e.preventDefault();

        // Get form data
        const formElement = document.getElementById('add-product-form');
        const formData = new FormData(formElement);

        // Add 'false' value for unchecked checkboxes
        formElement.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            if (!checkbox.checked) {
                formData.append(checkbox.name, 'false');
            } else {
                formData.set(checkbox.name, 'true'); // Ensures 'true' is set when checked
            }
        });

        // Perform validation
        const validationErrors = [];

        // Required fields
        const requiredFields = ['sku', 'title', 'brand', 'category_id', 'price'];
        requiredFields.forEach(field => {
            if (!formData.get(field)) {
                validationErrors.push(`${field} is required.`);
            }
        });

        // Additional checks
        const price = formData.get('price');
        if (price && isNaN(price)) {
            validationErrors.push('Price must be a number.');
        }

        // If there are validation errors, show an alert and return
        if (validationErrors.length > 0) {
            alert('Please fix the following errors:\n' + validationErrors.join('\n'));
            return;
        }

        // If validation passes, proceed with the form submission
        try {
            const response = await AjaxService.postForm('/addProduct', formData);
            if (response.success) {
                alert('Product added successfully');
                await this.loadProducts();
            } else {
                alert('Error adding product: ' + response.message);
            }
        } catch (error) {
            console.error('Error adding product:', error);
            alert('An error occurred while adding the product.');
        }
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

}
