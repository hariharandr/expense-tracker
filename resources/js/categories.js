
// import $ from 'jquery';
// import CategoryList from './components/category-list';
// import ExpenseList from './components/expense-list';

// $(document).ready(function () {
//     console.log("Categories script loaded!");

//     const categoryList = new window.CategoryList('#category-list-container');
//     const categoryForm = new window.CategoryForm('#add-category-form');

//     $(document).on('showCategoryEditForm', function (event, data) {
//         categoryForm.setEditMode(data.categoryId, data.categoryName);
//     });

//     $(document).on('categoryUpdated', function () {
//         categoryList.fetchCategories();
//         categoryForm.setAddMode();
//     });
// });

// In categories.js (or app.js if that's where you initialize)
// In categories.js
import CategoryList from './components/category-list';
import $ from 'jquery';

$(document).ready(function () {
    const targetNode = document.body; // Observe changes to the body
    const config = { childList: true, subtree: true }; // Observe added nodes

    const callback = function (mutationsList, observer) {
        for (const mutation of mutationsList) {
            if (mutation.type === 'childList') {
                const categoryListContainer = document.querySelector('#category-list-container');
                if (categoryListContainer) {
                    new CategoryList('#category-list-container');
                    observer.disconnect(); // Stop observing once initialized
                    return; // Important: Exit the loop
                }
            }
        }
    };

    const observer = new MutationObserver(callback);
    observer.observe(targetNode, config);

    // Handle the case where the element might already exist
    const categoryListContainer = document.querySelector('#category-list-container');
    if (categoryListContainer) {
        new CategoryList('#category-list-container');
        observer.disconnect();
    }
});