document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    window.loadDashboard = loadDashboard;

    function createProductCountDiv(stats) {
        const productCountDiv = DomHelper.createElement('div');
        const productCountLabel = DomHelper.createElement('label', {for: 'productCount'}, 'Products count:');
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

    function createCategoryCountDiv(stats) {
        const categoryCountDiv = DomHelper.createElement('div');
        const categoryCountLabel = DomHelper.createElement('label', {for: 'categoryCount'}, 'Categories count:');
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

    function createHomepageCountDiv(stats) {
        const homepageCountDiv = DomHelper.createElement('div');
        const homepageCountLabel = DomHelper.createElement('label', {for: 'homepageCount'}, 'Home page opening count:');
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

    function createMostViewedProductDiv(stats) {
        const mostViewedProductDiv = DomHelper.createElement('div');
        const mostViewedProductLabel = DomHelper.createElement('label', {for: 'mostViewedProduct'}, 'Most often viewed product:');
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

    function createProductViewsDiv(stats) {
        const productViewsDiv = DomHelper.createElement('div');
        const productViewsLabel = DomHelper.createElement('label', {for: 'productViews'}, 'Number of most viewed product views:');
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
     * Function to load the Dashboard page content
     */
    async function loadDashboard() {
        DomHelper.removeCssFile('/src/Application/Presentation/Public/css/categories.css');
        DomHelper.loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        contentDiv.innerHTML = '';

        const stats = await AjaxService.get('/getStatistics');

        const dashboardGrid = DomHelper.createElement('div', {class: 'dashboardGrid'});

        const productCountDiv = createProductCountDiv(stats);
        const categoryCountDiv = createCategoryCountDiv(stats);
        const homepageCountDiv = createHomepageCountDiv(stats);
        const mostViewedProductDiv = createMostViewedProductDiv(stats);
        const productViewsDiv = createProductViewsDiv(stats);
        const spacingDiv = DomHelper.createElement('div');

        dashboardGrid.appendChild(productCountDiv);
        dashboardGrid.appendChild(homepageCountDiv);
        dashboardGrid.appendChild(categoryCountDiv);
        dashboardGrid.appendChild(mostViewedProductDiv);
        dashboardGrid.appendChild(spacingDiv);
        dashboardGrid.appendChild(productViewsDiv);

        contentDiv.appendChild(dashboardGrid);
    }

});
