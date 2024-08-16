document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    const router = new Router(contentDiv);

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

    function loadCssFile(filename) {
        const existingLink = document.querySelector(`link[href="${filename}"]`);
        if (!existingLink) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = filename;
            document.head.appendChild(link);
        }
    }

    function removeCssFile(filename) {
        const existingLink = document.querySelector(`link[href="${filename}"]`);
        if (existingLink) {
            existingLink.remove();
        }
    }

    /**
     * Function to load the Dashboard page content
     */
    /**
     * Function to load the Dashboard page content
     */
    async function loadDashboard() {
        removeCssFile('/src/Application/Presentation/Public/css/categories.css');
        loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        contentDiv.innerHTML = '';

        // Send AJAX request to get statistics
        const stats = await ajaxGet('/getStatistics');

        const dashboardGrid = createElement('div', { class: 'dashboardGrid' });

        const productCountDiv = createElement('div');
        const productCountLabel = createElement('label', { for: 'productCount' }, 'Products count:');
        const productCountInput = createElement('input', {
            type: 'text',
            id: 'productCount',
            value: stats.productCount || '0',
            readonly: true
        });
        productCountDiv.appendChild(productCountLabel);
        productCountDiv.appendChild(productCountInput);

        const categoryCountDiv = createElement('div');
        const categoryCountLabel = createElement('label', { for: 'categoryCount' }, 'Categories count:');
        const categoryCountInput = createElement('input', {
            type: 'text',
            id: 'categoryCount',
            value: stats.categoryCount || '0',
            readonly: true
        });
        categoryCountDiv.appendChild(categoryCountLabel);
        categoryCountDiv.appendChild(categoryCountInput);

        const homepageCountDiv = createElement('div');
        const homepageCountLabel = createElement('label', { for: 'homepageCount' }, 'Home page opening count:');
        const homepageCountInput = createElement('input', {
            type: 'text',
            id: 'homepageCount',
            value: stats.homeViewCount || '0',
            readonly: true
        });
        homepageCountDiv.appendChild(homepageCountLabel);
        homepageCountDiv.appendChild(homepageCountInput);

        const mostViewedProductDiv = createElement('div');
        const mostViewedProductLabel = createElement('label', { for: 'mostViewedProduct' }, 'Most often viewed product:');
        const mostViewedProductInput = createElement('input', {
            type: 'text',
            id: 'mostViewedProduct',
            value: stats.mostViewedProduct ? stats.mostViewedProduct.title : 'N/A',
            readonly: true
        });
        mostViewedProductDiv.appendChild(mostViewedProductLabel);
        mostViewedProductDiv.appendChild(mostViewedProductInput);

        const productViewsDiv = createElement('div');
        const productViewsLabel = createElement('label', { for: 'productViews' }, 'Number of most viewed product views:');
        const productViewsInput = createElement('input', {
            type: 'text',
            id: 'productViews',
            value: stats.mostViewedProduct ? stats.mostViewedProduct.viewCount : '0',
            readonly: true
        });
        productViewsDiv.appendChild(productViewsLabel);
        productViewsDiv.appendChild(productViewsInput);

        const spacingDiv = createElement('div');

        dashboardGrid.appendChild(productCountDiv);
        dashboardGrid.appendChild(homepageCountDiv);
        dashboardGrid.appendChild(categoryCountDiv);
        dashboardGrid.appendChild(mostViewedProductDiv);
        dashboardGrid.appendChild(spacingDiv);
        dashboardGrid.appendChild(productViewsDiv);

        contentDiv.appendChild(dashboardGrid);
    }

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

    /**
     * Function to load content for the "Product Categories" page
     */
    function loadCategories() {
        contentDiv.innerHTML = '';

        removeCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        loadCssFile('/src/Application/Presentation/Public/css/categories.css');

        const categoryContainer = createElement('div', { class: 'category-container' });
        const categoryListDiv = createElement('div', { class: 'category-list' });

        const categories = [
            { id: 1, title: 'Laptops', parent: null, subcategories: [
                    { id: 2, title: 'Office', parent: 'Laptops', subcategories: [] },
                    { id: 3, title: 'Gaming', parent: 'Laptops', selected: true, subcategories: []},
                    { id: 4, title: 'Multimedia', parent: 'Laptops', subcategories: [
                            { id: 7, title: 'Office', parent: 'Processors' , subcategories: []},
                            { id: 8, title: 'Gaming', parent: 'Processors', subcategories: [] },
                            { id: 9, title: 'Multimedia', parent: 'Processors', subcategories: [] }
                        ] },
                ] },
            { id: 5, title: 'Processors', parent: null, subcategories: [
                    { id: 7, title: 'Office', parent: 'Processors' , subcategories: []},
                    { id: 8, title: 'Gaming', parent: 'Processors', subcategories: [] },
                    { id: 9, title: 'Multimedia', parent: 'Processors', subcategories: [] }
                ] },
            { id: 6, title: 'Motherboards', parent: null, subcategories: [
                    { id: 10, title: 'Office', parent: 'Motherboards', subcategories: [] },
                    { id: 11, title: 'Gaming', parent: 'Motherboards', subcategories: [] },
                    { id: 12, title: 'Multimedia', parent: 'Motherboards', subcategories: [] }
                ] }
        ];

        // Kreiranje forme za desnu stranu
        const selectedCategoryDiv = createElement('div', { class: 'selected-category' });

        function updateSelectedCategory(category) {
            selectedCategoryDiv.innerHTML = ''; // Brisanje prethodnog sadržaja forme

            const titleInput = createElement('input', { type: 'text', value: category.title });
            const parentCategoryInput = createElement('input', { type: 'text', value: category.parent });
            const codeInput = createElement('input', { type: 'text', value: `CODE-${category.id}` });
            const descriptionTextarea = createElement('textarea', {}, `Description for ${category.title}`);

            selectedCategoryDiv.appendChild(createElement('label', {}, 'Title:'));
            selectedCategoryDiv.appendChild(titleInput);

            selectedCategoryDiv.appendChild(createElement('label', {}, 'Parent category:'));
            selectedCategoryDiv.appendChild(parentCategoryInput);

            selectedCategoryDiv.appendChild(createElement('label', {}, 'Code:'));
            selectedCategoryDiv.appendChild(codeInput);

            selectedCategoryDiv.appendChild(createElement('label', {}, 'Description:'));
            selectedCategoryDiv.appendChild(descriptionTextarea);

            const deleteButton = createElement('button', { class: 'delete' }, 'Delete');
            const editButton = createElement('button', {}, 'Edit');

            selectedCategoryDiv.appendChild(deleteButton);
            selectedCategoryDiv.appendChild(editButton);
        }

        function createCategoryElement(category) {
            const categoryDiv = createElement('div', {
                class: 'category-item'
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
                    const subcategoryItem = createCategoryElement(subcategory);
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
                e.stopPropagation(); // Zaustavljanje "bubbling" kako bi se izbeglo bojenje pretka
                document.querySelectorAll('.category-item').forEach(item => {
                    item.classList.remove('selected');
                });
                categoryDiv.classList.add('selected');

                // Ažuriraj formu sa podacima o selektovanoj kategoriji
                updateSelectedCategory(category);
            });

            return categoryDiv;
        }

        categories.forEach(category => {
            const categoryElement = createCategoryElement(category);
            categoryListDiv.appendChild(categoryElement);
        });

        const addRootButton = createElement('button', {}, 'Add root category');
        const addSubButton = createElement('button', {}, 'Add subcategory');
        const addCategoryDiv = createElement('div', { class: 'add-category' });
        addCategoryDiv.appendChild(addRootButton);
        addCategoryDiv.appendChild(addSubButton);
        categoryListDiv.appendChild(addCategoryDiv);

        categoryContainer.appendChild(categoryListDiv);

        // Inicijalno prikazivanje forme sa podacima selektovane kategorije
        const selectedCategory = categories.flatMap(cat => cat.subcategories).find(cat => cat.selected);
        if (selectedCategory) {
            updateSelectedCategory(selectedCategory);
        }

        categoryContainer.appendChild(selectedCategoryDiv);

        contentDiv.appendChild(categoryContainer);
    }






    //loadDashboard();

    // Register routes with the router
    router.registerRoute('/admin/dashboard', loadDashboard);
    router.registerRoute('/admin/products', loadProducts);
    router.registerRoute('/admin/categories', loadCategories);

    // Initialize the router
    router.init();

    // Default to load the dashboard initially
    // Default to load the dashboard initially
    router.navigate('/admin/dashboard');


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

});