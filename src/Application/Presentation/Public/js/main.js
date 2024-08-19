document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    const router = new Router(contentDiv);

    window.removeCssFile = removeCssFile;
    window.loadCssFile = loadCssFile;
    window.createElement = createElement;

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
     * Loads a CSS file dynamically into the document's head.
     * If the CSS file is already loaded, it won't load it again.
     *
     * @param {string} filename - The path to the CSS file to load.
     */
    function loadCssFile(filename) {
        const existingLink = document.querySelector(`link[href="${filename}"]`);
        if (!existingLink) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = filename;
            document.head.appendChild(link);
        }
    }

    /**
     * Removes a CSS file dynamically from the document's head.
     * If the CSS file is not found, it does nothing.
     *
     * @param {string} filename - The path to the CSS file to remove.
     */
    function removeCssFile(filename) {
        const existingLink = document.querySelector(`link[href="${filename}"]`);
        if (existingLink) {
            existingLink.remove();
        }
    }
});