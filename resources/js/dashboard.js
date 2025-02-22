import { showLoading, hideLoading } from './components/loading'; // Import loading functions
import ChartRenderer from './components/chart';
import $ from 'jquery';
import axios from 'axios';

class Dashboard {
    constructor() {
        console.log("Dashboard.js loaded");
        this.startDateInput = $('#start_date');
        this.endDateInput = $('#end_date');
        this.categoryFilter = $('#category_filter');
        this.applyFilterButton = $('#apply-filter');
        this.expenseListContainer = $('#expense-list-container');
        this.noExpensesMessage = $('#no-expenses');
        this.errorMessage = $('#error-message');
        // this.chartCanvas = document.getElementById('expense-summary-chart');  <-- Remove this line

        this.expenses = [];
        this.chart = null;

        this.populateCategories();
        this.applyFilterButton.on('click', this.fetchExpenses.bind(this)); // Bind filter event
    }

    populateCategories() {
        showLoading(); // Show loading while fetching categories
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
                hideLoading(); // Hide loading after categories are loaded
            })
            .catch(error => {
                console.error("Error fetching categories:", error);
                this.showError("Failed to load categories.");
                hideLoading(); // Hide loading even on error
            });
    }

    fetchExpenses() {
        showLoading();
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
                this.renderExpenses(); // Render expenses FIRST
                this.renderChart(); // Then render the chart
                hideLoading(); // THEN hide the loading overlay
            })
            .catch(error => {
                console.error("Error fetching expenses:", error);
                this.showError("Failed to load expenses.");
                hideLoading(); // Hide even on error
            });
    }


    renderExpenses() {
        const $tableBody = $('#expense-list-container');  // Select the <tbody>
        $tableBody.empty(); // Clear existing rows

        if (this.expenses.length === 0) {
            this.noExpensesMessage.removeClass('hidden');
            return;
        }

        this.expenses.forEach(expense => {
            const row = $('<tr>').addClass('border-b border-gray-200 dark:border-gray-600');
            const amountCell = $('<td>').addClass('px-6 py-4 whitespace-nowrap text-left').text(`$${expense.amount}`);
            const descriptionCell = $('<td>').addClass('px-6 py-4 text-left').text(expense.description);
            const categoryCell = $('<td>').addClass('px-6 py-4 whitespace-nowrap text-left').text(expense.category.name);

            row.append(amountCell, descriptionCell, categoryCell);
            $tableBody.append(row); // Append to the <tbody>
        });
    }
    renderChart() {
        const chartCanvas = document.getElementById('expenseChart'); // Use document.getElementById
        if (!chartCanvas) {
            console.error("Canvas element not found!"); // Check the console
            return;
        }

        if (this.chart) {
            this.chart.destroy();
        }

        const chartRenderer = new ChartRenderer();
        chartRenderer.render(this.expenses, chartCanvas); // Pass expenses and canvas
        this.chart = chartRenderer.chart; // Store the chart instance
    }

    showError(message) {
        this.errorMessage.removeClass('hidden');
        const errorMessageText = this.errorMessage.find('p');
        errorMessageText.text(message);
    }
}

export default Dashboard; 