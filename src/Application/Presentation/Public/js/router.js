/**
 * Router class to handle client-side routing in a single-page application.
 */
class Router {
    constructor(contentDiv) {
        this.routes = {};
        this.contentDiv = contentDiv;
    }

    /**
     * Registers a new route with a corresponding handler function.
     *
     * @param {string} path - The URL path for the route.
     * @param {Function} handler - The function to execute when the route is accessed.
     */
    registerRoute(path, handler) {
        this.routes[path] = handler;
    }

    /**
     * Navigates to a specific route, executes the handler, and updates the URL.
     *
     * @param {string} path - The URL path to navigate to.
     */
    loadContent(path) {
        if (this.contentDiv.getAttribute('data-current-path') === path) {
            console.log(`Content for ${path} is already loaded.`);
            return;
        }

        switch (path) {
            case '/admin':
                removeCssFile('/src/Application/Presentation/Public/css/categories.css');
                loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
                loadDashboard();
                break;
            case '/admin/products':
                loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
                loadProducts();
                break;
            case '/admin/categories':
                removeCssFile('/src/Application/Presentation/Public/css/dashboard.css');
                loadCssFile('/src/Application/Presentation/Public/css/categories.css');
                loadCategories();
                break;
            default:
                removeCssFile('/src/Application/Presentation/Public/css/categories.css');
                loadCssFile('/src/Application/Presentation/Public/css/dashboard.css');
                loadDashboard();
                break;
        }

        this.contentDiv.setAttribute('data-current-path', path);
    }

    navigate(path) {
        if (window.location.pathname !== path) {
            history.replaceState({}, '', path);
        }
        this.loadContent(path);
        this.updateActiveMenu(path); // Osvježava aktivni meni
    }

    /**
     * Handles the route by executing the associated handler function.
     *
     * @param {string} path - The URL path to handle.
     */
    handleRoute(path) {
        this.loadContent(path);
        this.updateActiveMenu(path); // Osvježava aktivni meni
    }

    updateActiveMenu(path) {
        const menuItems = document.querySelectorAll('.sideMenu ul li');
        menuItems.forEach(item => {
            item.classList.remove('active');
            const section = item.getAttribute('data-section');
            if (`/${section}` === path) {
                item.classList.add('active');
            }
        });
    }

    /**
     * Initializes the router by setting up event listeners for link clicks
     * and handling the initial route on page load.
     */
    init() {
        window.addEventListener('popstate', () => {
            this.handleRoute(window.location.pathname);
        });

        document.addEventListener('click', (event) => {
            if (event.target.matches('a')) {
                event.preventDefault();
                const path = event.target.getAttribute('href');
                this.navigate(path);
            }
        });

        this.handleRoute(window.location.pathname);
    }
}
