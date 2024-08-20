document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    window.loadCategories = loadCategories;

    /**
     * Recursively searches for a category by its ID within a nested categories structure.
     *
     * @param {number} id - The ID of the category to find.
     * @param {Array} categories - The array of categories to search through.
     * @returns {object|null} - The found category object or null if not found.
     */
    function findCategoryById(id, categories) {
        for (const category of categories) {
            if (category.id === id) {
                return category;
            } else if (category.subcategories.length) {
                const found = findCategoryById(id, category.subcategories);
                if (found) return found;
            }
        }
        return null;
    }

    /**
     * Updates the selected category's details in the right-hand form, displaying the category's information.
     *
     * @param {object} category - The category object containing details to display.
     * @param {Array} categories - The array of all categories for searching parent categories.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the category details will be displayed.
     */
    function updateSelectedCategory(category, categories, selectedCategoryDiv) {
        selectedCategoryDiv.innerHTML = '';

        const header = createElement('div', {class: 'selected-category-header'}, 'Selected category');
        selectedCategoryDiv.appendChild(header);

        const parentCategory = findCategoryById(category.parentId, categories);
        const parentCategoryName = parentCategory ? parentCategory.title : 'Root';

        const titleInput = createElement('input', {type: 'text', value: category.title});
        const parentCategoryInput = createElement('input', {
            type: 'text',
            value: parentCategoryName,
            readonly: true
        });
        const codeInput = createElement('input', {type: 'text', value: category.code});
        const descriptionTextarea = createElement('textarea', {}, category.description);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Title:'));
        selectedCategoryDiv.appendChild(titleInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Parent category:'));
        selectedCategoryDiv.appendChild(parentCategoryInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Code:'));
        selectedCategoryDiv.appendChild(codeInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Description:'));
        selectedCategoryDiv.appendChild(descriptionTextarea);

        const deleteButton = createElement('button', {class: 'delete'}, 'Delete');
        const editButton = createElement('button', {}, 'Edit');

        selectedCategoryDiv.appendChild(deleteButton);
        selectedCategoryDiv.appendChild(editButton);

        deleteButton.addEventListener('click', async () => {
            const confirmation = confirm('Are you sure you want to delete this category? This action cannot be undone.');

            if (confirmation) {
                const response = await ajaxDelete('/deleteCategory', JSON.stringify({id: category.id}));

                if (response.success) {
                    alert(response.message);
                    await loadCategories();
                } else {
                    alert(response.message);
                }
            }
        });
    }

    /**
     * Creates a DOM element representing a category and its subcategories, with the ability to toggle visibility.
     *
     * @param {object} category - The category object containing details to create the element.
     * @param {Array} categories - The array of all categories for searching parent categories.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the category details will be displayed.
     * @returns {HTMLElement} - The created DOM element representing the category.
     */
    function createCategoryElement(category, categories, selectedCategoryDiv) {
        const categoryDiv = createElement('div', {
            class: 'category-item',
            'data-id': category.id
        });

        const toggleBtn = createElement('span', {
            class: 'toggle-btn'
        }, category.subcategories.length ? '+' : '');

        const categoryTitle = createElement('span', {}, category.title);
        categoryDiv.appendChild(toggleBtn);
        categoryDiv.appendChild(categoryTitle);

        if (category.subcategories.length) {
            const subcategoryDiv = createElement('div', {
                class: 'subcategory-list hidden'
            });

            category.subcategories.forEach(subcategory => {
                const subcategoryItem = createCategoryElement(subcategory, categories, selectedCategoryDiv);
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
            updateSelectedCategory(category, categories, selectedCategoryDiv);
        });

        return categoryDiv;
    }

    /**
     * Function to create a form for adding or editing a category.
     *
     * @param {string} type - The type of category being added ('root' or 'subcategory').
     * @param {object|null} parentCategory - The parent category if adding a subcategory, otherwise null.
     * @param {HTMLElement} selectedCategoryDiv - The DOM element where the form will be displayed.
     * @param {Array} categories - The array of all categories.
     */
    function createCategoryForm(type, parentCategory, selectedCategoryDiv, categories) {
        selectedCategoryDiv.innerHTML = '';

        const headerText = type === 'root' ? 'Create root category' : 'Create subcategory';
        const header = createElement('div', {class: 'selected-category-header'}, headerText);
        selectedCategoryDiv.appendChild(header);

        const titleInput = createElement('input', {type: 'text', placeholder: 'Enter category title'});
        const parentCategoryInput = createElement('input', {
            type: 'text',
            value: parentCategory ? parentCategory.title : 'Root',
            readonly: true
        });
        const codeInput = createElement('input', {type: 'text', placeholder: 'Enter category code'});
        const descriptionTextarea = createElement('textarea', {}, 'Enter description');

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Title:'));
        selectedCategoryDiv.appendChild(titleInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Parent category:'));
        selectedCategoryDiv.appendChild(parentCategoryInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Code:'));
        selectedCategoryDiv.appendChild(codeInput);

        selectedCategoryDiv.appendChild(createElement('label', {}, 'Description:'));
        selectedCategoryDiv.appendChild(descriptionTextarea);

        const cancelButton = createElement('button', {}, 'Cancel');
        const saveButton = createElement('button', {}, 'OK');

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

            const response = await ajaxPost('/addCategory', newCategory);

            if (response.success) {
                alert(response.message);
                await loadCategories();
            } else {
                alert(response.message);
            }
        });
    }

    /**
     * Function to load content for the "Product Categories" page
     */
    async function loadCategories() {
        contentDiv.innerHTML = '';

        removeCssFile('/src/Application/Presentation/Public/css/dashboard.css');
        loadCssFile('/src/Application/Presentation/Public/css/categories.css');

        const categoryContainer = createElement('div', {class: 'category-container'});
        const categoryListDiv = createElement('div', {class: 'category-list'});

        const categories = await ajaxGet('/getCategories');

        const selectedCategoryDiv = createElement('div', {class: 'selected-category'});

        categories.forEach(category => {
            const categoryElement = createCategoryElement(category, categories, selectedCategoryDiv);
            categoryListDiv.appendChild(categoryElement);
        });

        const addRootButton = createElement('button', {}, 'Add root category');
        const addSubButton = createElement('button', {}, 'Add subcategory');
        const addCategoryDiv = createElement('div', {class: 'add-category'});
        addCategoryDiv.appendChild(addRootButton);
        addCategoryDiv.appendChild(addSubButton);
        categoryListDiv.appendChild(addCategoryDiv);

        categoryContainer.appendChild(categoryListDiv);

        const selectedCategory = categories.flatMap(cat => cat.subcategories).find(cat => cat.selected);
        if (selectedCategory) {
            updateSelectedCategory(selectedCategory, categories, selectedCategoryDiv);
        }

        categoryContainer.appendChild(selectedCategoryDiv);

        contentDiv.appendChild(categoryContainer);

        addRootButton.addEventListener('click', () => {
            createCategoryForm('root', null, selectedCategoryDiv, categories);
        });

        addSubButton.addEventListener('click', () => {
            const selectedCategoryElement = document.querySelector('.category-item.selected');
            if (selectedCategoryElement) {
                const selectedCategoryId = selectedCategoryElement.dataset.id;
                const parentCategory = findCategoryById(parseInt(selectedCategoryId), categories);
                createCategoryForm('subcategory', parentCategory, selectedCategoryDiv, categories);
            } else {
                alert('Please select a category to add a subcategory.');
            }
        });
    }
});