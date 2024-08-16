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
    navigate(path) {
        window.history.pushState({}, path, window.location.origin + path);
        this.handleRoute(path);
    }

    /**
     * Handles the route by executing the associated handler function.
     *
     * @param {string} path - The URL path to handle.
     */
    handleRoute(path) {
        const handler = this.routes[path];
        if (handler) {
            handler(this.contentDiv);
        } else {
            console.error(`No handler found for route: ${path}`);
        }
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