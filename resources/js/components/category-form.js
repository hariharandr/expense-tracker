import { showLoading, hideLoading } from './loading';
import $ from 'jquery';
import axios from 'axios';

class CategoryForm {
    constructor(formId) {
        this.form = $(formId);
        this.form.on('submit', this.handleSubmit.bind(this));
        this.nameInput = $('#name');
        this.nameError = $('#name-error');
        this.categoryListContainer = $('#category-list-container');
        this.noCategoriesMessage = $('#no-categories');
        this.errorMessage = $('#error-message');
        this.categories = [];

        this.fetchCategories();
    }

    fetchCategories() {
        showLoading(); // Show loading before API call
        this.errorMessage.addClass('hidden');

        axios.get('/api/categories')
            .then(response => {
                this.categories = response.data;
                hideLoading(); // Hide loading after categories are loaded
                this.renderCategories();
            })
            .catch(error => {
                console.error("Error fetching categories:", error);
                this.showError("Failed to load categories.");
                hideLoading(); // Hide loading even on error
            });
    }

    handleSubmit(event) {
        event.preventDefault();
        this.resetErrors();
        const isValid = this.validateForm();

        if (isValid) {
            showLoading(); // Show loading before API call
            this.errorMessage.addClass('hidden');

            const formData = this.form.serialize();

            axios.post(this.form.attr('action'), formData, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    hideLoading(); // Hide loading after successful API call
                    this.form[0].reset();
                    this.fetchCategories();
                    alert('Category added successfully!');
                })
                .catch(error => {
                    console.error("Error submitting category:", error);
                    hideLoading(); // Hide loading even on error
                    const errorMessage = _.get(error, 'response.data.errors', "An error occurred. Please try again.");
                    this.showError(errorMessage);
                    if (typeof errorMessage === 'object') {
                        for (const field in errorMessage) {
                            const errorElement = $(`#${field}-error`);
                            if (errorElement) {
                                errorElement.text(errorMessage[field][0]);
                            }
                        }
                    }
                });
        }
    }

    validateForm() {
        let isValid = true;
        const name = this.nameInput.val();

        if (!name) {
            this.nameError.text("Name is required.");
            isValid = false;
        }

        return isValid;
    }

    resetErrors() {
        this.nameError.text("");
    }

    renderCategories() {
        this.categoryListContainer.empty();
        this.noCategoriesMessage.addClass('hidden');

        if (this.categories.length === 0) {
            this.noCategoriesMessage.removeClass('hidden');
            return;
        }

        this.categories.forEach(category => {
            const categoryItem = $('<div>').addClass('border-b border-gray-200 py-2');
            const name = $('<span>').text(`Name: ${category.name}`);
            categoryItem.append(name);
            this.categoryListContainer.append(categoryItem);
        });
    }

    showError(message) {
        this.errorMessage.removeClass('hidden');
        const errorMessageText = this.errorMessage.find('p');
        errorMessageText.text(message);
    }
}

window.CategoryForm = CategoryForm;