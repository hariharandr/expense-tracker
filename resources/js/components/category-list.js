import axios from 'axios';
import $ from 'jquery';
import Loading from './loading'; // Import the loading component
$(document).ready(function () { // Wrap your code in $(document).ready()
    if (window.location.pathname === '/categories' || window.location.pathname === '/expenses/edit') {
        new CategoryList('#category-list-container'); // Initialize CategoryList
        console.log("CategoryList component loaded!");
    }

});

class CategoryList {
    constructor(containerId) {
        this.container = document.querySelector(containerId);
        if (!this.container) {
            console.error("Category list container not found.");
            return;
        }
        this.fetchCategories();
    }

    fetchCategories() {
        const loadingIndicator = document.getElementById('loading-indicator');
        const noCategoriesMessage = document.getElementById('no-categories');
        const errorMessage = document.getElementById('error-message');

        if (!loadingIndicator) {
            console.error("Loading indicator element not found!");
            return; // Stop execution if element is not found
        }

        if (!noCategoriesMessage) {
            console.error("No categories message element not found!");
            return; // Stop execution if element is not found
        }
        if (!errorMessage) {
            console.error("Error message element not found!");
            return; // Stop execution if element is not found
        }

        errorMessage.classList.add('hidden'); // Hide error message

        axios.get('/api/categories')
            .then(response => {
                const categories = response.data;
                this.renderCategories(categories);
                loadingIndicator.classList.add('hidden'); // Hide loading indicator
                if (categories.length === 0) {
                    noCategoriesMessage.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error(error);
                loadingIndicator.classList.add('hidden'); // Hide loading indicator
                errorMessage.classList.remove('hidden'); // Show error message
            });
    }

    renderCategories(categories) {
        if (!this.container) {
            console.error("Category list container not found.");
            return;
        }

        this.container.innerHTML = ''; // Clear existing content
        if (categories.length === 0) {
            this.container.innerHTML = "<p>No categories found.</p>";
            return;
        }

        const ul = document.createElement('ul');
        categories.forEach(category => {
            const li = document.createElement('li');
            li.textContent = category.name;

            // Add edit and delete buttons (example)
            const editButton = document.createElement('button');
            editButton.textContent = 'Edit';
            editButton.addEventListener('click', () => {
                // Trigger an event or call a function to handle editing
                $(document).trigger('showCategoryEditForm', { categoryId: category.id, categoryName: category.name });
            });
            li.appendChild(editButton);

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.addEventListener('click', () => {
                // Trigger an event or call a function to handle deleting
                if (confirm("Are you sure you want to delete this category?")) {
                    this.deleteCategory(category.id);
                }
            });
            li.appendChild(deleteButton);

            ul.appendChild(li);
        });
        this.container.appendChild(ul);
    }

    deleteCategory(categoryId) {
        axios.delete(`/api/categories/${categoryId}`)
            .then(response => {
                this.fetchCategories(); // Refresh the category list
            })
            .catch(error => {
                console.error("Error deleting category:", error);
                // Handle error (e.g., display an error message)
            });
    }
}

export default CategoryList;