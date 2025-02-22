import './bootstrap'; // Keep this line

import Alpine from 'alpinejs'; // Keep this line

window.Alpine = Alpine; // Keep this line

Alpine.start(); // Keep this line

import $ from 'jquery'; // Add this line
import Chart from 'chart.js/auto'; // Add this line

// Component Imports (Will be added later)
import CategoryList from './components/category-list';
import CategoryForm from './components/category-form';
import Loading from './components/loading';
import ChartComponent from './components/chart';
import ExpenseList from './components/expense-list';
import ExpenseForm from './components/expense-form';

import('./dashboard');
import('./categories');

$(document).ready(function () {
    console.log("Document is ready! (From revised app.js)");

    // Initialize Components (Will be added later)
    const loading = new Loading('#loading-indicator');
    // if page /dashboard import dashboard.js
    // if (window.location.pathname === '/dashboard' || window.location.pathname === '/expenses' || window.location.pathname === '/expenses/add' || window.location.pathname === '/expenses/edit') {
    console.log("Dashboard script loaded!");
    const expenseList = new ExpenseList('#expense-list-container');
    const expenseForm = new ExpenseForm('#add-expense-form');
    const chart = new ChartComponent('#expense-summary-chart');
    // }

    // if page /categories import categories.js
    // if (window.location.pathname === '/categories') {
    console.log("Categories script loaded!");
    const categoryList = new CategoryList('#category-list-container');
    const categoryForm = new CategoryForm('#add-category-form');
    // }
});
