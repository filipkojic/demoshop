document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    window.loadProducts = loadProducts;

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
});