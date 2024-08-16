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

    /**
     * Function to load the Dashboard page content
     */
    /**
     * Function to load the Dashboard page content
     */
    async function loadDashboard() {
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
        const categoriesHeading = createElement('h2', {}, 'Product Categories Section');
        const noDataMessage = createElement('p', {}, 'No data available.');
        contentDiv.appendChild(categoriesHeading);
        contentDiv.appendChild(noDataMessage);
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