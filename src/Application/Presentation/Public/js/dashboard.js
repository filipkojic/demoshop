document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    const router = new Router(contentDiv);

    window.loadDashboard = loadDashboard;
    window.loadProducts = loadProducts;
    window.loadCategories = loadCategories;
    window.removeCssFile = removeCssFile;
    window.loadCssFile = loadCssFile;

    // Register routes with the router
    router.registerRoute('/admin', loadDashboard);
    router.registerRoute('/admin/products', loadProducts);
    router.registerRoute('/admin/categories', loadCategories);

    // Initialize the router
    router.init();

    // Handle side menu navigation clicks
    const menuItems = document.querySelectorAll('.sideMenu ul li');
    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            const sectionId = this.getAttribute('data-section');
            router.navigate(`/${sectionId}`);
        });
    });


    /**
     * Function to create an HTML element
     * @param {string} tag - The HTML tag to create (e.g., 'div', 'span', 'input')
     * @param {object} attributes - The attributes to add to the element (e.g., { id: 'myId', class: 'myClass' })
     * @param {string} textContent - The text content to set inside the element (optional)
     * @returns {HTMLElement} - The created HTML element
     */
    function createElement(tag, attributes = {}, textContent = '') {
        const element = document.createElement(tag);
        for (const [key, value] of Object.entries(attributes)) {
            element.setAttribute(key, value);
        }
        if (textContent) {
            element.textContent = textContent;
        }
        return element;
    }

    /**
     * Loads a CSS file dynamically into the document's head.
     * If the CSS file is already loaded, it won't load it again.
     *
     * @param {string} filename - The path to the CSS file to load.
     */
    function loadCssFile(filename) {
        const existingLink = document.querySelector(`link[href="${filename}"]`);
        if (!existingLink) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = filename;
            document.head.appendChild(link);
        }
    }

    /**
     * Removes a CSS file dynamically from the document's head.
     * If the CSS file is not found, it does nothing.
     *
     * @param {string} filename - The path to the CSS file to remove.
     */
    function removeCssFile(filename) {
        const existingLink = document.querySelector(`link[href="${filename}"]`);
        if (existingLink) {
            existingLink.remove();
        }
    }

    // ****************** DASHBOARD ***********************
    function createProductCountDiv(stats) {
        const productCountDiv = createElement('div');
        const productCountLabel = createElement('label', {for: 'productCount'}, 'Products count:');
        const productCountInput = createElement('input', {
            type: 'text',
            id: 'productCount',
            value: stats.productCount || '0',
            readonly: true
        });
        productCountDiv.appendChild(productCountLabel);
        productCountDiv.appendChild(productCountInput);
        return productCountDiv;
    }

    function createCategoryCountDiv(stats) {
        const categoryCountDiv = createElement('div');
        const categoryCountLabel = createElement('label', {for: 'categoryCount'}, 'Categories count:');
        const categoryCountInput = createElement('input', {
            type: 'text',
            id: 'categoryCount',
            value: stats.categoryCount || '0',
            readonly: true
        });
        categoryCountDiv.appendChild(categoryCountLabel);
        categoryCountDiv.appendChild(categoryCountInput);
        return categoryCountDiv;
    }

    function createHomepageCountDiv(stats) {
        const homepageCountDiv = createElement('div');
        const homepageCountLabel = createElement('label', {for: 'homepageCount'}, 'Home page opening count:');
        const homepageCountInput = createElement('input', {
            type: 'text',
            id: 'homepageCount',
            value: stats.homeViewCount || '0',
            readonly: true
        });
        homepageCountDiv.appendChild(homepageCountLabel);
        homepageCountDiv.appendChild(homepageCountInput);
        return homepageCountDiv;
    }

    function createMostViewedProductDiv(stats) {
        const mostViewedProductDiv = createElement('div');
        const mostViewedProductLabel = createElement('label', {for: 'mostViewedProduct'}, 'Most often viewed product:');
        const mostViewedProductInput = createElement('input', {
            type: 'text',
            id: 'mostViewedProduct',
            value: stats.mostViewedProduct ? stats.mostViewedProduct.title : 'N/A',
            readonly: true
        });
        mostViewedProductDiv.appendChild(mostViewedProductLabel);
        mostViewedProductDiv.appendChild(mostViewedProductInput);
        return mostViewedProductDiv;
    }

    function createProductViewsDiv(stats) {
        const productViewsDiv = createElement('div');
        const productViewsLabel = createElement('label', {for: 'productViews'}, 'Number of most viewed product views:');
        const productViewsInput = createElement('input', {
            type: 'text',
            id: 'productViews',
            value: stats.mostViewedProduct ? stats.mostViewedProduct.viewCount : '0',
            readonly: true
        });
        productViewsDiv.appendChild(productViewsLabel);
        productViewsDiv.appendChild(productViewsInput);
        return productViewsDiv;
    }

    /**
     * Function to load the Dashboard page content
     */
    async function loadDashboard() {
        removeCssFile('/src/Application/Presentation/Public/css/categories.css');
        loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        contentDiv.innerHTML = '';

        const stats = await ajaxGet('/getStatistics');

        const dashboardGrid = createElement('div', {class: 'dashboardGrid'});

        const productCountDiv = createProductCountDiv(stats);
        const categoryCountDiv = createCategoryCountDiv(stats);
        const homepageCountDiv = createHomepageCountDiv(stats);
        const mostViewedProductDiv = createMostViewedProductDiv(stats);
        const productViewsDiv = createProductViewsDiv(stats);
        const spacingDiv = createElement('div');

        dashboardGrid.appendChild(productCountDiv);
        dashboardGrid.appendChild(homepageCountDiv);
        dashboardGrid.appendChild(categoryCountDiv);
        dashboardGrid.appendChild(mostViewedProductDiv);
        dashboardGrid.appendChild(spacingDiv);
        dashboardGrid.appendChild(productViewsDiv);

        contentDiv.appendChild(dashboardGrid);
    }


    // ****************** PRODUCTS ***********************

    /**
     * Function to load content for the "Products" page
     */
    function loadProducts() {
        contentDiv.innerHTML = '';
        const productsHeading = createElement('h2', {}, 'Products Section');
        const noDataMessage = createElement('p', {}, 'No data available.');
        contentDiv.appendChild(productsHeading);
        contentDiv.appendChild(noDataMessage);
    }

    // ****************** CATEGORIES ***********************

    /**
     * Recursively searches for a category by its ID within a nested categories structure.
     *
     * @param {number} id - The ID of the category to find.
     * @param {Array} categories - The array of categories to search through.
     * @returns {object|null} - The found category object or null if not found.
     */
    function findCategoryById(id, categories) {
        for (const category of categories) {
            if (category.id === id) {
                return category;
            } else if (category.subcategories.length) {
                const found = findCategoryById(id, category.subcategories);
                if (found) return found;
            }
        }
        return null;
    }

    /**
     * Updates the selected category's details in the right-hand form, displaying the category's information.
     *
     * @param {object} category - The category object containing details to display.
     * @param {Array} categories - The array of all categories for searching parent categories.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the category details will be displayed.
     */
    function updateSelectedCategory(category, categories, selectedCategoryDiv) {
        selectedCategoryDiv.innerHTML = '';

        const header = createElement('div', {class: 'selected-category-header'}, 'Selected category');
        selectedCategoryDiv.appendChild(header);

        const parentCategory = findCategoryById(category.parentId, categories);
        const parentCategoryName = parentCategory ? parentCategory.title : 'Root category';

        const titleInput = createElement('input', {type: 'text', value: category.title});
        const parentCategoryInput = createElement('input', {
            type: 'text',
            value: parentCategoryName,
            readonly: true
        });
        const codeInput = createElement('input', {type: 'text', value: `CODE-${category.id}`});
        const descriptionTextarea = createElement('textarea', {}, `Description for ${category.title}`);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Title:'));
        selectedCategoryDiv.appendChild(titleInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Parent category:'));
        selectedCategoryDiv.appendChild(parentCategoryInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Code:'));
        selectedCategoryDiv.appendChild(codeInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Description:'));
        selectedCategoryDiv.appendChild(descriptionTextarea);

        const deleteButton = createElement('button', {class: 'delete'}, 'Delete');
        const editButton = createElement('button', {}, 'Edit');

        selectedCategoryDiv.appendChild(deleteButton);
        selectedCategoryDiv.appendChild(editButton);
    }

    /**
     * Creates a DOM element representing a category and its subcategories, with the ability to toggle visibility.
     *
     * @param {object} category - The category object containing details to create the element.
     * @param {Array} categories - The array of all categories for searching parent categories.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the category details will be displayed.
     * @returns {HTMLElement} - The created DOM element representing the category.
     */
    function createCategoryElement(category, categories, selectedCategoryDiv) {
        const categoryDiv = createElement('div', {
            class: 'category-item',
            'data-id': category.id
        });

        const toggleBtn = createElement('span', {
            class: 'toggle-btn'
        }, category.subcategories.length ? '+' : '');

        const categoryTitle = createElement('span', {}, category.title);
        categoryDiv.appendChild(toggleBtn);
        categoryDiv.appendChild(categoryTitle);

        if (category.subcategories.length) {
            const subcategoryDiv = createElement('div', {
                class: 'subcategory-list hidden'
            });

            category.subcategories.forEach(subcategory => {
                const subcategoryItem = createCategoryElement(subcategory, categories, selectedCategoryDiv);
                subcategoryDiv.appendChild(subcategoryItem);
            });

            categoryDiv.appendChild(subcategoryDiv);

            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = subcategoryDiv.classList.toggle('hidden');
                toggleBtn.textContent = isHidden ? '+' : '-';
            });
        }

        categoryDiv.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.category-item').forEach(item => {
                item.classList.remove('selected');
            });
            categoryDiv.classList.add('selected');
            // alert(`Selected Category ID: ${category.id}`);
            // selectedCategoryId = category.id;
            // selectedCategoryName = category.title;
            updateSelectedCategory(category, categories, selectedCategoryDiv);
        });

        return categoryDiv;
    }


    // let selectedCategoryId = null;
    // let selectedCategoryName = null;

    /**
     * Function to create a form for adding or editing a category.
     *
     * @param {string} type - The type of category being added ('root' or 'subcategory').
     * @param {object|null} parentCategory - The parent category if adding a subcategory, otherwise null.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the form will be displayed.
     * @param {Array} categories - The array of all categories.
     */
    function createCategoryForm(type, parentCategory, selectedCategoryDiv, categories) {
        selectedCategoryDiv.innerHTML = '';

        // if (type === 'subcategory') {
        //     parentCategory = {
        //         id: selectedCategoryId,
        //         title: selectedCategoryName
        //     };
        // }

        const headerText = type === 'root' ? 'Create root category' : 'Create subcategory';
        const header = createElement('div', {class: 'selected-category-header'}, headerText);
        selectedCategoryDiv.appendChild(header);

        const titleInput = createElement('input', {type: 'text', placeholder: 'Enter category title'});
        const parentCategoryInput = createElement('input', {
            type: 'text',
            value: parentCategory ? parentCategory.title : 'Root',
            readonly: true
        });
        const codeInput = createElement('input', {type: 'text', placeholder: 'Enter category code'});
        const descriptionTextarea = createElement('textarea', {}, 'Enter description');

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Title:'));
        selectedCategoryDiv.appendChild(titleInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Parent category:'));
        selectedCategoryDiv.appendChild(parentCategoryInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Code:'));
        selectedCategoryDiv.appendChild(codeInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Description:'));
        selectedCategoryDiv.appendChild(descriptionTextarea);

        const cancelButton = createElement('button', {}, 'Cancel');
        const saveButton = createElement('button', {}, 'OK');

        selectedCategoryDiv.appendChild(cancelButton);
        selectedCategoryDiv.appendChild(saveButton);

        cancelButton.addEventListener('click', () => {
            selectedCategoryDiv.innerHTML = ''; // Clear the form
        });

        saveButton.addEventListener('click', async () => {
            const newCategory = {
                title: titleInput.value,
                code: codeInput.value,
                description: descriptionTextarea.value,
                parentId: parentCategory ? parentCategory.id : null
            };

            const response = await ajaxPost('/addCategory', newCategory);
            if (response.success) {
                await loadCategories();
            } else {
                alert('Failed to add category. Please try again.');
            }
        });
    }

    /**
     * Function to load content for the "Product Categories" page
     */
    async function loadCategories() {
        contentDiv.innerHTML = '';

        removeCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        loadCssFile('/src/Application/Presentation/Public/css/categories.css');

        const categoryContainer = createElement('div', {class: 'category-container'});
        const categoryListDiv = createElement('div', {class: 'category-list'});

        const categories = await ajaxGet('/getCategories');

        const selectedCategoryDiv = createElement('div', {class: 'selected-category'});

        categories.forEach(category => {
            const categoryElement = createCategoryElement(category, categories, selectedCategoryDiv);
            categoryListDiv.appendChild(categoryElement);
        });

        const addRootButton = createElement('button', {}, 'Add root category');
        const addSubButton = createElement('button', {}, 'Add subcategory');
        const addCategoryDiv = createElement('div', {class: 'add-category'});
        addCategoryDiv.appendChild(addRootButton);
        addCategoryDiv.appendChild(addSubButton);
        categoryListDiv.appendChild(addCategoryDiv);

        categoryContainer.appendChild(categoryListDiv);

        const selectedCategory = categories.flatMap(cat => cat.subcategories).find(cat => cat.selected);
        if (selectedCategory) {
            updateSelectedCategory(selectedCategory, categories, selectedCategoryDiv);
        }

        categoryContainer.appendChild(selectedCategoryDiv);

        contentDiv.appendChild(categoryContainer);

        addRootButton.addEventListener('click', () => {
            createCategoryForm('root', null, selectedCategoryDiv, categories);
        });

        addSubButton.addEventListener('click', () => {
            const selectedCategoryElement = document.querySelector('.category-item.selected');
            if (selectedCategoryElement) {
                const selectedCategoryId = selectedCategoryElement.dataset.id;
                const parentCategory = findCategoryById(parseInt(selectedCategoryId), categories);
                createCategoryForm('subcategory', parentCategory, selectedCategoryDiv, categories);
            } else {
                alert('Please select a category to add a subcategory.');
            }
        });
    }

});
