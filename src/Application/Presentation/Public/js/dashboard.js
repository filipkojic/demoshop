document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    const menuItems = document.querySelectorAll('.sideMenu ul li');

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
    function loadDashboard() {
        contentDiv.innerHTML = '';

        const dashboardGrid = createElement('div', {class: 'dashboardGrid'});

        const productCountDiv = createElement('div');
        const productCountLabel = createElement('label', {for: 'productCount'}, 'Products count:');
        const productCountInput = createElement('input', {
            type: 'text',
            id: 'productCount',
            value: '0',
            readonly: true
        });
        productCountDiv.appendChild(productCountLabel);
        productCountDiv.appendChild(productCountInput);

        const categoryCountDiv = createElement('div');
        const categoryCountLabel = createElement('label', {for: 'categoryCount'}, 'Categories count:');
        const categoryCountInput = createElement('input', {
            type: 'text',
            id: 'categoryCount',
            value: '0',
            readonly: true
        });
        categoryCountDiv.appendChild(categoryCountLabel);
        categoryCountDiv.appendChild(categoryCountInput);

        const homepageCountDiv = createElement('div');
        const homepageCountLabel = createElement('label', {for: 'homepageCount'}, 'Home page opening count:');
        const homepageCountInput = createElement('input', {
            type: 'text',
            id: 'homepageCount',
            value: '0',
            readonly: true
        });
        homepageCountDiv.appendChild(homepageCountLabel);
        homepageCountDiv.appendChild(homepageCountInput);

        const mostViewedProductDiv = createElement('div');
        const mostViewedProductLabel = createElement('label', {for: 'mostViewedProduct'}, 'Most often viewed product:');
        const mostViewedProductInput = createElement('input', {
            type: 'text',
            id: 'mostViewedProduct',
            value: 'prod 1',
            readonly: true
        });
        mostViewedProductDiv.appendChild(mostViewedProductLabel);
        mostViewedProductDiv.appendChild(mostViewedProductInput);

        const productViewsDiv = createElement('div');
        const productViewsLabel = createElement('label', {for: 'productViews'}, 'Number of prod1 views:');
        const productViewsInput = createElement('input', {
            type: 'text',
            id: 'productViews',
            value: '0',
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

    loadDashboard();

    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            const sectionId = this.getAttribute('data-section');

            if (sectionId === 'dashboard') {
                loadDashboard();
            } else if (sectionId === 'products') {
                loadProducts();
            } else if (sectionId === 'categories') {
                loadCategories();
            }
        });
    });
});
