/**
 * DashboardController class to manage the content of the "Dashboard" page.
 * Implements the Singleton pattern to ensure only one instance is used across the application.
 */
class DashboardController {
    constructor(contentDivId) {
        if (DashboardController.instance) {
            return DashboardController.instance;
        }

        this.contentDiv = document.getElementById(contentDivId);
        DashboardController.instance = this;

        return this;
    }

    /**
     * Creates a div element with product count information.
     * @param {object} stats - The statistics object containing product count.
     * @returns {HTMLElement} The created div element.
     */
    createProductCountDiv(stats) {
        const productCountDiv = DomHelper.createElement('div');
        const productCountLabel = DomHelper.createElement('label', { for: 'productCount' }, 'Products count:');
        const productCountInput = DomHelper.createElement('input', {
            type: 'text',
            id: 'productCount',
            value: stats.productCount || '0',
            readonly: true
        });
        productCountDiv.appendChild(productCountLabel);
        productCountDiv.appendChild(productCountInput);
        return productCountDiv;
    }

    /**
     * Creates a div element with category count information.
     * @param {object} stats - The statistics object containing category count.
     * @returns {HTMLElement} The created div element.
     */
    createCategoryCountDiv(stats) {
        const categoryCountDiv = DomHelper.createElement('div');
        const categoryCountLabel = DomHelper.createElement('label', { for: 'categoryCount' }, 'Categories count:');
        const categoryCountInput = DomHelper.createElement('input', {
            type: 'text',
            id: 'categoryCount',
            value: stats.categoryCount || '0',
            readonly: true
        });
        categoryCountDiv.appendChild(categoryCountLabel);
        categoryCountDiv.appendChild(categoryCountInput);
        return categoryCountDiv;
    }

    /**
     * Creates a div element with homepage opening count information.
     * @param {object} stats - The statistics object containing homepage view count.
     * @returns {HTMLElement} The created div element.
     */
    createHomepageCountDiv(stats) {
        const homepageCountDiv = DomHelper.createElement('div');
        const homepageCountLabel = DomHelper.createElement('label', { for: 'homepageCount' }, 'Home page opening count:');
        const homepageCountInput = DomHelper.createElement('input', {
            type: 'text',
            id: 'homepageCount',
            value: stats.homeViewCount || '0',
            readonly: true
        });
        homepageCountDiv.appendChild(homepageCountLabel);
        homepageCountDiv.appendChild(homepageCountInput);
        return homepageCountDiv;
    }

    /**
     * Creates a div element with information about the most viewed product.
     * @param {object} stats - The statistics object containing the most viewed product information.
     * @returns {HTMLElement} The created div element.
     */
    createMostViewedProductDiv(stats) {
        const mostViewedProductDiv = DomHelper.createElement('div');
        const mostViewedProductLabel = DomHelper.createElement('label', { for: 'mostViewedProduct' }, 'Most often viewed product:');
        const mostViewedProductInput = DomHelper.createElement('input', {
            type: 'text',
            id: 'mostViewedProduct',
            value: stats.mostViewedProduct ? stats.mostViewedProduct.title : 'N/A',
            readonly: true
        });
        mostViewedProductDiv.appendChild(mostViewedProductLabel);
        mostViewedProductDiv.appendChild(mostViewedProductInput);
        return mostViewedProductDiv;
    }

    /**
     * Creates a div element with view count of the most viewed product.
     * @param {object} stats - The statistics object containing the view count of the most viewed product.
     * @returns {HTMLElement} The created div element.
     */
    createProductViewsDiv(stats) {
        const productViewsDiv = DomHelper.createElement('div');
        const productViewsLabel = DomHelper.createElement('label', { for: 'productViews' }, 'Number of most viewed product views:');
        const productViewsInput = DomHelper.createElement('input', {
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
     * Loads content for the Dashboard page.
     * Fetches statistics data and dynamically creates elements to display the data.
     */
    async loadDashboard() {
        DomHelper.removeCssFile('/src/Application/Presentation/Public/css/categories.css');
        DomHelper.loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        this.contentDiv.innerHTML = '';

        const stats = await AjaxService.get('/getStatistics');

        const dashboardGrid = DomHelper.createElement('div', { class: 'dashboardGrid' });

        const productCountDiv = this.createProductCountDiv(stats);
        const categoryCountDiv = this.createCategoryCountDiv(stats);
        const homepageCountDiv = this.createHomepageCountDiv(stats);
        const mostViewedProductDiv = this.createMostViewedProductDiv(stats);
        const productViewsDiv = this.createProductViewsDiv(stats);
        const spacingDiv = DomHelper.createElement('div');

        dashboardGrid.appendChild(productCountDiv);
        dashboardGrid.appendChild(homepageCountDiv);
        dashboardGrid.appendChild(categoryCountDiv);
        dashboardGrid.appendChild(mostViewedProductDiv);
        dashboardGrid.appendChild(spacingDiv);
        dashboardGrid.appendChild(productViewsDiv);

        this.contentDiv.appendChild(dashboardGrid);
    }

    /**
     * Retrieves the single instance of DashboardController.
     * If the instance does not exist, it creates it using the provided contentDivId.
     * @returns {DashboardController} The single instance of DashboardController.
     */
    static getInstance() {
        if (!DashboardController.instance) {
            DashboardController.instance = new DashboardController('content');
        }
        return DashboardController.instance;
    }
}

