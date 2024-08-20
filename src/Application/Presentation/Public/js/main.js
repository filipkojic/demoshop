document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    const router = new Router(contentDiv);

    window.removeCssFile = DomHelper.removeCssFile;
    window.loadCssFile = DomHelper.loadCssFile;
    window.createElement = DomHelper.createElement;

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
});