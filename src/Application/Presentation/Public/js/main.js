document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    const router = new Router(contentDiv);

    // Register routes with the router
    router.registerRoute('/admin', () => {
        //const dashboardController = DashboardController.getInstance();
        DomHelper.removeCssFile('/src/Application/Presentation/Public/css/categories.css');
        DomHelper.loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        //dashboardController.loadDashboard();
        loadDashboard();
    });

    router.registerRoute('/admin/products', () => {
        const productsController = ProductsController.getInstance();
        DomHelper.loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        productsController.loadProducts();
    });

    router.registerRoute('/admin/categories', () => {
        //const categoriesController = CategoriesController.getInstance();
        DomHelper.removeCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        DomHelper.loadCssFile('/src/Application/Presentation/Public/css/categories.css');
        //categoriesController.loadCategories();
        loadCategories();
    });

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
});
