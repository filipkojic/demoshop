/**
 * CategoriesController class to manage the content of the "Categories" page.
 * Implements the Singleton pattern to ensure only one instance is used across the application.
 */
class CategoriesController {
    /**
     * Creates an instance of CategoriesController or returns the existing instance.
     * @param {string} contentDivId - The ID of the div where the content will be rendered.
     * @returns {CategoriesController} The single instance of ProductsController.
     */
    constructor(contentDivId) {
        if (CategoriesController.instance) {
            return CategoriesController.instance;
        }

        this.contentDiv = document.getElementById(contentDivId);
        CategoriesController.instance = this;

        return this;
    }

    /**
     * Recursively searches for a category by its ID within a nested categories structure.
     * @param {number} id - The ID of the category to find.
     * @param {Array} categories - The array of categories to search through.
     * @returns {object|null} - The found category object or null if not found.
     */
    findCategoryById(id, categories) {
        for (const category of categories) {
            if (category.id === id) {
                return category;
            } else if (category.subcategories.length) {
                const found = this.findCategoryById(id, category.subcategories);
                if (found) return found;
            }
        }
        return null;
    }

    /**
     * Updates the selected category's details in the right-hand form, displaying the category's information.
     * @param {object} category - The category object containing details to display.
     * @param {Array} categories - The array of all categories for searching parent categories.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the category details will be displayed.
     */
    updateSelectedCategory(category, categories, selectedCategoryDiv) {
        selectedCategoryDiv.innerHTML = '';

        const header = DomHelper.createElement('div', { class: 'selected-category-header' }, 'Selected category');
        selectedCategoryDiv.appendChild(header);

        const parentCategory = this.findCategoryById(category.parentId, categories);
        const parentCategoryName = parentCategory ? parentCategory.title : 'Root';

        const titleInput = DomHelper.createElement('input', { type: 'text', value: category.title });
        const parentCategoryInput = DomHelper.createElement('input', {
            type: 'text',
            value: parentCategoryName,
            readonly: true
        });
        const codeInput = DomHelper.createElement('input', { type: 'text', value: category.code });
        const descriptionTextarea = DomHelper.createElement('textarea', {}, category.description);

        selectedCategoryDiv.appendChild(DomHelper.createElement('label', {}, 'Title:'));
        selectedCategoryDiv.appendChild(titleInput);

        selectedCategoryDiv.appendChild(DomHelper.createElement('label', {}, 'Parent category:'));
        selectedCategoryDiv.appendChild(parentCategoryInput);

        selectedCategoryDiv.appendChild(DomHelper.createElement('label', {}, 'Code:'));
        selectedCategoryDiv.appendChild(codeInput);

        selectedCategoryDiv.appendChild(DomHelper.createElement('label', {}, 'Description:'));
        selectedCategoryDiv.appendChild(descriptionTextarea);

        const deleteButton = DomHelper.createElement('button', { class: 'delete' }, 'Delete');
        const editButton = DomHelper.createElement('button', {}, 'Edit');

        selectedCategoryDiv.appendChild(deleteButton);
        selectedCategoryDiv.appendChild(editButton);

        deleteButton.addEventListener('click', () => this.handleDeleteCategory(category));
    }

    /**
     * Creates a DOM element representing a category and its subcategories, with the ability to toggle visibility.
     * @param {object} category - The category object containing details to create the element.
     * @param {Array} categories - The array of all categories for searching parent categories.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the category details will be displayed.
     * @returns {HTMLElement} - The created DOM element representing the category.
     */
    createCategoryElement(category, categories, selectedCategoryDiv) {
        const categoryDiv = DomHelper.createElement('div', {
            class: 'category-item',
            'data-id': category.id
        });

        const toggleBtn = DomHelper.createElement('span', {
            class: 'toggle-btn'
        }, category.subcategories.length ? '+' : '');

        const categoryTitle = DomHelper.createElement('span', {}, category.title);
        categoryDiv.appendChild(toggleBtn);
        categoryDiv.appendChild(categoryTitle);

        if (category.subcategories.length) {
            const subcategoryDiv = DomHelper.createElement('div', {
                class: 'subcategory-list hidden'
            });

            category.subcategories.forEach(subcategory => {
                const subcategoryItem = this.createCategoryElement(subcategory, categories, selectedCategoryDiv);
                subcategoryDiv.appendChild(subcategoryItem);
            });

            categoryDiv.appendChild(subcategoryDiv);

            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = subcategoryDiv.classList.toggle('hidden');
                toggleBtn.textContent = isHidden ? '+' : '-';
            });
        }

        categoryDiv.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.category-item').forEach(item => {
                item.classList.remove('selected');
            });
            categoryDiv.classList.add('selected');
            this.updateSelectedCategory(category, categories, selectedCategoryDiv);
        });

        return categoryDiv;
    }

    /**
     * Function to create a form for adding or editing a category.
     * @param {string} type - The type of category being added ('root' or 'subcategory').
     * @param {object|null} parentCategory - The parent category if adding a subcategory, otherwise null.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the form will be displayed.
     * @param {Array} categories - The array of all categories.
     */
    createCategoryForm(type, parentCategory, selectedCategoryDiv, categories) {
        selectedCategoryDiv.innerHTML = '';

        const headerText = type === 'root' ? 'Create root category' : 'Create subcategory';
        const header = DomHelper.createElement('div', { class: 'selected-category-header' }, headerText);
        selectedCategoryDiv.appendChild(header);

        const titleInput = DomHelper.createElement('input', { type: 'text', placeholder: 'Enter category title' });
        const parentCategoryInput = DomHelper.createElement('input', {
            type: 'text',
            value: parentCategory ? parentCategory.title : 'Root',
            readonly: true
        });
        const codeInput = DomHelper.createElement('input', { type: 'text', placeholder: 'Enter category code' });
        const descriptionTextarea = DomHelper.createElement('textarea', {}, 'Enter description');

        selectedCategoryDiv.appendChild(DomHelper.createElement('label', {}, 'Title:'));
        selectedCategoryDiv.appendChild(titleInput);

        selectedCategoryDiv.appendChild(DomHelper.createElement('label', {}, 'Parent category:'));
        selectedCategoryDiv.appendChild(parentCategoryInput);

        selectedCategoryDiv.appendChild(DomHelper.createElement('label', {}, 'Code:'));
        selectedCategoryDiv.appendChild(codeInput);

        selectedCategoryDiv.appendChild(DomHelper.createElement('label', {}, 'Description:'));
        selectedCategoryDiv.appendChild(descriptionTextarea);

        const cancelButton = DomHelper.createElement('button', {}, 'Cancel');
        const saveButton = DomHelper.createElement('button', {}, 'OK');

        selectedCategoryDiv.appendChild(cancelButton);
        selectedCategoryDiv.appendChild(saveButton);

        cancelButton.addEventListener('click', () => {
            selectedCategoryDiv.innerHTML = ''; // Clear the form
        });

        saveButton.addEventListener('click', async () => {
            const title = titleInput.value.trim();
            const code = codeInput.value.trim();

            if (!title || !code) {
                alert('Fields title and code are required.');
                return;
            }

            const isCodeUnique = !categories.some(category => category.code === code);
            if (!isCodeUnique) {
                alert('Code must be unique.');
                return;
            }

            const newCategory = JSON.stringify({
                title: title,
                code: code,
                description: descriptionTextarea.value,
                parent_id: parentCategory ? parentCategory.id : null
            });

            try {
                const response = await AjaxService.post('/addCategory', newCategory);

                if (response.success) {
                    alert(response.message);
                    await this.loadCategories();
                } else {
                    alert(response.message);
                }
            } catch (error) {
                console.error('Error adding category:', error);
                alert('An error occurred while adding the category. Please try again later.');
            }

            if (response.success) {
                alert(response.message);
                await this.loadCategories();
            } else {
                alert(response.message);
            }
        });
    }

    /**
     * Function to load content for the "Product Categories" page.
     */
    async loadCategories() {
        this.contentDiv.innerHTML = '';

        DomHelper.removeCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        DomHelper.loadCssFile('/src/Application/Presentation/Public/css/categories.css');

        const categoryContainer = DomHelper.createElement('div', { class: 'category-container' });
        const categoryListDiv = DomHelper.createElement('div', { class: 'category-list' });

        let categories;
        try {
            categories = await AjaxService.get('/getCategories');
        } catch (error) {
            console.error('Error loading categories:', error);
            alert('An error occurred while loading the categories. Please try again later.');
            return;
        }

        const selectedCategoryDiv = DomHelper.createElement('div', { class: 'selected-category' });

        categories.forEach(category => {
            const categoryElement = this.createCategoryElement(category, categories, selectedCategoryDiv);
            categoryListDiv.appendChild(categoryElement);
        });

        const addRootButton = DomHelper.createElement('button', {}, 'Add root category');
        const addSubButton = DomHelper.createElement('button', {}, 'Add subcategory');
        const addCategoryDiv = DomHelper.createElement('div', { class: 'add-category' });
        addCategoryDiv.appendChild(addRootButton);
        addCategoryDiv.appendChild(addSubButton);
        categoryListDiv.appendChild(addCategoryDiv);

        categoryContainer.appendChild(categoryListDiv);

        const selectedCategory = categories.flatMap(cat => cat.subcategories).find(cat => cat.selected);
        if (selectedCategory) {
            this.updateSelectedCategory(selectedCategory, categories, selectedCategoryDiv);
        }

        categoryContainer.appendChild(selectedCategoryDiv);

        this.contentDiv.appendChild(categoryContainer);

        addRootButton.addEventListener('click', () => {
            this.createCategoryForm('root', null, selectedCategoryDiv, categories);
        });

        addSubButton.addEventListener('click', () => {
            const selectedCategoryElement = document.querySelector('.category-item.selected');
            if (selectedCategoryElement) {
                const selectedCategoryId = selectedCategoryElement.dataset.id;
                const parentCategory = this.findCategoryById(parseInt(selectedCategoryId), categories);
                this.createCategoryForm('subcategory', parentCategory, selectedCategoryDiv, categories);
            } else {
                alert('Please select a category to add a subcategory.');
            }
        });
    }

    /**
     * Retrieves the single instance of CategoriesController.
     * If the instance does not exist, it creates it using the provided contentDivId.
     * @returns {CategoriesController} The single instance of CategoriesController.
     */
    static getInstance() {
        if (!CategoriesController.instance) {
            CategoriesController.instance = new CategoriesController('content');
        }
        return CategoriesController.instance;
    }


    // Handler methods

    /**
     * Handles the delete category action.
     * @param {object} category - The category object that is being deleted.
     */
    async handleDeleteCategory(category) {
        const confirmation = confirm('Are you sure you want to delete this category? This action cannot be undone.');

        if (confirmation) {
            try {
                const response = await AjaxService.delete('/deleteCategory', JSON.stringify({ id: category.id }));

                if (response.success) {
                    alert(response.message);
                    await this.loadCategories();
                } else {
                    alert(response.message);
                }
            } catch (error) {
                console.error('Error deleting category:', error);
                alert('An error occurred while deleting the category. Please try again later.');
            }
        }
    }

}
