import axios from 'axios';
import $ from 'jquery';
import Loading from './loading'; // Import the loading component

class ExpenseForm {
    constructor(formSelector, isEditing = false, expenseId = null) {
        this.form = $(formSelector);
        this.isEditing = isEditing;
        this.expenseId = expenseId;
        this.loading = new Loading('#loading-indicator'); // Initialize loading component
        this.categories = []; // Store categories

        this.loadCategories(); // Load categories on initialization

        if (this.isEditing) {
            this.loadExpenseData(); // Load expense data if editing
        }

        this.form.submit(this.handleSubmit.bind(this));
    }


    loadCategories() {
        axios.get('/api/categories')
            .then(response => {
                console.log("callled categories");
                this.categories = response.data;
                this.populateCategorySelect();
            })
            .catch(error => {
                console.error("Error loading categories:", error);
            });
    }

    populateCategorySelect() {
        const categorySelect = this.form.find('#category_id'); // Assuming your select has this ID
        categorySelect.empty(); // Clear existing options
        categorySelect.append('<option value="">Select Category</option>'); // Add default option

        this.categories.forEach(category => {
            console.log()
            categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
        });

        if (this.isEditing) {
            const selectedCategoryId = this.form.find('#category_id').data('selected');
            categorySelect.val(selectedCategoryId);
        }
    }


    loadExpenseData() {
        this.loading.show();
        axios.get(`/api/expenses/${this.expenseId}`)
            .then(response => {
                const expense = response.data;
                this.form.find('#amount').val(expense.amount);
                this.form.find('#description').val(expense.description);
                this.form.find('#expense_date').val(expense.expense_date);
                this.form.find('#category_id').data('selected', expense.category_id); // Store selected category
                this.populateCategorySelect(); // Populate categories after data is loaded
            })
            .catch(error => {
                console.error("Error loading expense data:", error);
            })
            .finally(() => {
                this.loading.hide();
            });
    }

    handleSubmit(event) {
        event.preventDefault();
        this.loading.show(); // Show loading indicator

        const data = this.form.serialize();
        const url = this.isEditing ? `/api/expenses/${this.expenseId}` : '/api/expenses';
        const method = this.isEditing ? 'PUT' : 'POST';

        axios({
            method: method,
            url: url,
            data: data
        })
            .then(response => {
                alert(this.isEditing ? 'Expense updated successfully!' : 'Expense added successfully!');
                this.form.trigger('reset'); // Clear the form
                if (!this.isEditing) {
                    // Redirect or refresh the expense list if not editing
                    window.location.href = '/expenses'; // Example: Redirect to the expense list page
                }
            })
            .catch(error => {
                console.error("Error submitting expense:", error);
                if (error.response && error.response.data && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    for (const key in errors) {
                        const errorMessages = errors[key].join('<br>');
                        $(`#${key}-error`).html(errorMessages); // Display error messages
                    }
                } else {
                    alert('An error occurred while submitting the expense.');
                }
            })
            .finally(() => {
                this.loading.hide(); // Hide loading indicator
            });
    }
}

export default ExpenseForm;