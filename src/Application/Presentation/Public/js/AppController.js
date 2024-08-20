/**
 * Main application class
 */
class AppController {

    /**
     * Constructs the AppController instance, initializes the content div, router, and menu items.
     *
     * @param {string} contentDivId - The ID of the content div element where the main content will be displayed.
     */
    constructor(contentDivId) {
        this.contentDiv = document.getElementById(contentDivId);
        this.router = new Router(this.contentDiv);
        this.menuItems = document.querySelectorAll('.sideMenu ul li');
    }

    /**
     * Registers routes with the router and associates them with specific controllers.
     */
    registerRoutes() {
        this.router.registerRoute('/admin', () => {
            const dashboardController = DashboardController.getInstance();
            DomHelper.removeCssFile('/src/Application/Presentation/Public/css/categories.css');
            DomHelper.loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
            dashboardController.loadDashboard();
        });

        this.router.registerRoute('/admin/products', () => {
            const productsController = ProductsController.getInstance();
            DomHelper.loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
            productsController.loadProducts();
        });

        this.router.registerRoute('/admin/categories', () => {
            const categoriesController = CategoriesController.getInstance();
            DomHelper.removeCssFile('/src/Application/Presentation/Public/css/dashboard.css');
            DomHelper.loadCssFile('/src/Application/Presentation/Public/css/categories.css');
            categoriesController.loadCategories();
        });
    }

    /**
     * Handles side menu navigation clicks.
     */
    handleMenuClicks() {
        this.menuItems.forEach(item => {
            item.addEventListener('click', () => {
                this.menuItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                const sectionId = item.getAttribute('data-section');
                this.router.navigate(`/${sectionId}`);
            });
        });
    }

    /**
     * Initializes the application by registering routes, initializing the router, and setting up event listeners.
     */
    init() {
        this.registerRoutes();
        this.router.init();
        this.handleMenuClicks();
    }

    /**
     * Static method to initialize the AppController.
     */
    static init() {
        const appController = new AppController('content');
        appController.init();
    }
}

// Initialize the AppController when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    AppController.init();
});
