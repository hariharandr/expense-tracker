import axios from 'axios';
import $ from 'jquery';

class ExpenseForm {
    constructor(formId) {
        this.form = $(formId);
        this.form.on('submit', this.handleSubmit.bind(this));
        this.amountError = $('#amount-error');
        this.descriptionError = $('#description-error');
        this.categoryError = $('#category_id-error');
        this.dateError = $('#expense_date-error');
        this.loadingIndicator = $('#loading-indicator');
        this.errorMessage = $('#error-message');
        this.categorySelect = $('#category_id');

        this.populateCategories();
    }

    populateCategories() {
        axios.get('/api/categories') // Your API endpoint
            .then(response => {
                const categories = response.data;
                categories.forEach(category => {
                    const option = $('<option>', {
                        value: category.id,
                        text: category.name
                    });
                    this.categorySelect.append(option);
                });
            })
            .catch(error => {
                console.error("Error fetching categories:", error);
                this.showError("Failed to load categories.");
            });
    }

    handleSubmit(event) {
        event.preventDefault();

        this.resetErrors();
        const isValid = this.validateForm();

        if (isValid) {
            this.loadingIndicator.removeClass('hidden');
            this.errorMessage.addClass('hidden');

            const formData = new FormData(this.form[0]); // Get the underlying DOM element

            axios.post(this.form.attr('action'), formData, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    this.loadingIndicator.addClass('hidden');
                    this.form[0].reset(); // Reset the form using the DOM element
                    alert('Expense added successfully!'); // Or a more user-friendly message
                    window.location.href = '/dashboard'; // Redirect to dashboard
                })
                .catch(error => {
                    console.error("Error submitting expense:", error);
                    this.loadingIndicator.addClass('hidden');
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

        const amount = $('#amount').val();
        const description = $('#description').val();
        const categoryId = $('#category_id').val();
        const expenseDate = $('#expense_date').val();

        if (!amount || amount <= 0) {
            this.amountError.text("Amount is required and must be greater than zero.");
            isValid = false;
        }

        if (!description) {
            this.descriptionError.text("Description is required.");
            isValid = false;
        }

        if (!categoryId) {
            this.categoryError.text("Category is required.");
            isValid = false;
        }

        if (!expenseDate) {
            this.dateError.text("Expense Date is required.");
            isValid = false;
        }

        return isValid;
    }

    resetErrors() {
        this.amountError.text("");
        this.descriptionError.text("");
        this.categoryError.text("");
        this.dateError.text("");
    }

    showError(message) {
        this.errorMessage.removeClass('hidden');
        const errorMessageText = this.errorMessage.find('p'); // Use .find() for descendants
        if (typeof message === 'string') {
            errorMessageText.text(message);
        } else if (typeof message === 'object') { // Assuming error is an object
            errorMessageText.text(JSON.stringify(message)); // Display the whole object
        } else {
            errorMessageText.text("An error occurred. Please try again.");
        }
    }
}

export default ExpenseForm;
