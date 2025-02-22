import axios from 'axios';
import $ from 'jquery';
import Loading from './loading';

class ExpenseList {
    constructor(containerSelector) {
        this.container = $(containerSelector);
        this.loading = new Loading('#loading-indicator'); // Initialize loading component
        this.fetchExpenses();
    }

    fetchExpenses() {
        this.loading.show(); // Show loading indicator
        let start_date = new Date();
        start_date.setMonth(start_date.getMonth() - 2);
        console.log("start_date", start_date);
        let end_date = new Date();

        axios.get('/api/expenses', {
            params: {
                // start date from today to 2 months before end date today dynamically should acquire time
                start_date: start_date,
                end_date: end_date
            }
        })
            .then(response => {
                this.renderExpenses(response.data);
            })
            .catch(error => {
                console.error("Error fetching expenses:", error);
                this.container.html('<p>Error loading expenses.</p>');
            })
            .finally(() => {
                this.loading.hide(); // Hide loading indicator
            });
    }

    renderExpenses(expenses) {
        this.container.empty(); // Clear existing content

        if (expenses.length === 0) {
            this.container.html('<p>No expenses found.</p>');
            return;
        }

        const table = $('<table>').addClass('w-full text-sm text-left text-gray-500 dark:text-gray-400');
        const thead = $('<thead>').addClass('text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400');
        thead.append('<tr><th class="px-6 py-3">Category</th><th class="px-6 py-3">Amount</th><th class="px-6 py-3">Date</th><th class="px-6 py-3">Actions</th></tr>');
        table.append(thead);

        const tbody = $('<tbody>').addClass('bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700');
        expenses.forEach(expense => {
            console.log("expense", expense);
            const row = $('<tr>').addClass('hover:bg-gray-10 dark:hover:bg-gray-60');
            row.append(`<td class="px-6 py-4">${expense.category.name}</td>`);
            row.append(`<td class="px-6 py-4">${expense.amount}</td>`);
            row.append(`<td class="px-6 py-4">${expense.expense_date}</td>`);

            // Edit and Delete links
            const actionsCell = $('<td class="px-6 py-4">');
            const editLink = $(`<a href="/expenses/${expense.id}/edit" class="text-blue-600 hover:f-5 hover:text-blue-800 mr-2">Edit</a>`); // Added margin-right
            const deleteLink = $(`<a href="#" class="text-red-600 hover:text-red-800" data-expense-id="${expense.id}">Delete</a>`); // Use data attribute

            deleteLink.click(this.handleDelete.bind(this)); // Bind delete handler

            actionsCell.append(editLink).append(deleteLink);
            row.append(actionsCell);
            tbody.append(row);


        });
        table.append(tbody);
        this.container.append(table);
    }

    handleDelete(event) {
        const expenseId = $(event.target).data('expense-id');
        if (confirm('Are you sure you want to delete this expense?')) {
            this.loading.show();
            axios.delete(`/api/expenses/${expenseId}`)
                .then(() => {
                    this.fetchExpenses(); // Refresh the list
                    alert('Expense deleted successfully.');
                })
                .catch(error => {
                    console.error('Error deleting expense:', error);
                    alert('Failed to delete expense.');
                })
                .finally(() => {
                    this.loading.hide();
                });
        }
    }
}

export default ExpenseList;