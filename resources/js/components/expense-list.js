import { showLoading, hideLoading } from './loading'; // Import loading functions
import $ from 'jquery';
import axios from 'axios';

class ExpensesList {
    constructor() {
        this.expenseListContainer = $('#expense-list-container');
        this.noExpensesMessage = $('#no-expenses');
        this.errorMessage = $('#error-message');
        this.expenses = [];
        this.startDateInput = $('#start_date');
        this.endDateInput = $('#end_date');
        this.categoryFilter = $('#category_filter');
        this.applyFilterButton = $('#apply-filter');

        this.populateCategories();
        this.fetchExpenses();
        this.applyFilterButton.on('click', this.fetchExpenses.bind(this));
    }

    populateCategories() {
        showLoading();
        axios.get('/api/categories')
            .then(response => {
                const categories = response.data;
                categories.forEach(category => {
                    const option = $('<option>', {
                        value: category.id,
                        text: category.name
                    });
                    this.categoryFilter.append(option);
                });
                hideLoading();
            })
            .catch(error => {
                console.error("Error fetching categories:", error);
                this.showError("Failed to load categories.");
                hideLoading();
            });
    }

    fetchExpenses() {
        showLoading(); // Show loading before API call
        this.errorMessage.addClass('hidden');
        const startDate = this.startDateInput.val();
        const endDate = this.endDateInput.val();
        const categoryId = this.categoryFilter.val();

        const params = {};
        if (startDate) params.start_date = startDate;
        if (endDate) params.end_date = endDate;
        if (categoryId) params.category_id = categoryId;

        axios.get('/api/expenses', { params })
            .then(response => {
                this.expenses = response.data;
                hideLoading(); // Hide loading after expenses are loaded
                this.renderExpenses();
            })
            .catch(error => {
                console.error("Error fetching expenses:", error);
                this.showError("Failed to load expenses.");
                hideLoading(); // Hide loading even on error
            });
    }

    renderExpenses() {
        this.expenseListContainer.empty();
        this.noExpensesMessage.addClass('hidden');

        if (this.expenses.length === 0) {
            this.noExpensesMessage.removeClass('hidden');
            return;
        }

        this.expenses.forEach(expense => {
            const expenseItem = $('<div>').addClass('border-b border-gray-200 py-2');
            const amount = $('<span>').text(`Amount: $${expense.amount}`).addClass('mr-4');
            const description = $('<span>').text(`Description: ${expense.description}`).addClass('mr-4');
            const category = $('<span>').text(`Category: ${expense.category ? expense.category.name : 'N/A'}`);
            expenseItem.append(amount, description, category);
            this.expenseListContainer.append(expenseItem);
        });
    }

    showError(message) {
        this.errorMessage.removeClass('hidden');
        const errorMessageText = this.errorMessage.find('p');
        errorMessageText.text(message);
    }
}

export default ExpensesList;
