import $ from 'jquery';
import ExpenseList from './components/expense-list'; // Correct relative path
import ChartComponent from './components/chart';

$(document).ready(function () {

    const expenseList = new ExpenseList('#expense-list-container'); // No window.
    const chart = new ChartComponent('#expense-summary-chart');
});