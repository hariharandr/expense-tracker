import ExpenseForm from './components/expense-form';
import Dashboard from './dashboard'; // Import the module
import ExpensesList from './components/expense-list';
import $ from 'jquery';
// import chart from 'chart.js';

$(document).ready(function () {
    const expenseForm = new ExpenseForm('#add-expense-form');
    const dashboard = new Dashboard(); // Create an instance
    const expensesList = new ExpensesList(); // Create an instance

    // You can now use dashboard and expensesList
});