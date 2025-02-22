import axios from 'axios';
import $ from 'jquery';
import Loading from './loading'; // Import the loading component

class CategoryForm {
    constructor(formSelector) {
        this.form = $(formSelector);
        this.loading = new Loading('#loading-indicator');
        this.form.submit(this.handleSubmit.bind(this));
        this.isEditing = false; // Initially not editing
        this.categoryId = null;
    }

    setEditMode(categoryId, categoryName) {
        this.isEditing = true;
        this.categoryId = categoryId;
        this.form.find('#name').val(categoryName); // Pre-fill the form
        this.form.find('#category-form-title').text('Edit Category'); // Change title
    }

    setAddMode() {
        this.isEditing = false;
        this.categoryId = null;
        this.form.find('#name').val(''); // Clear the form
        this.form.find('#category-form-title').text('Add Category'); // Change title
    }

    handleSubmit(event) {
        console.log("Form submitted! for category name");
        event.preventDefault();
        this.loading.show();

        const data = this.form.serialize();
        const url = this.isEditing ? `/api/categories/${this.categoryId}` : '/api/categories';
        const method = this.isEditing ? 'PUT' : 'POST';

        axios({
            method: method,
            url: url,
            data: data,
            withCredentials: true,
            withXSRFToken: true
        })
            .then(response => {
                alert(this.isEditing ? 'Category updated successfully!' : 'Category added successfully!');
                this.form.trigger('reset');
                $(document).trigger('categoryUpdated'); // Trigger custom event
                this.setAddMode(); // Reset to add mode
            })
            .catch(error => {
                console.error("Error submitting category:", error);
                if (error.response && error.response.data && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    for (const key in errors) {
                        const errorMessages = errors[key].join('<br>');
                        $(`#${key}-error`).html(errorMessages); // Display error messages
                    }
                } else {
                    alert('An error occurred while submitting the category.');
                }
            })
            .finally(() => {
                this.loading.hide();
            });
    }
}

export default CategoryForm;