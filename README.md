# Expense Tracker

This project is an expense tracking application built using Laravel 11, MySQL, Redis, and jQuery. It provides users with a platform to manage their expenses, categorize them, and visualize their spending patterns through charts and detailed tables. This README documents the project setup, development environment, implemented features, and project structure.

## Table of Contents

-   Introduction
-   Features
-   Technologies Used
-   Development Environment Setup
-   Project Structure
-   Usage
-   API Endpoints
-   Database Schema
-   Testing
-   Future Enhancements

## Introduction

This expense tracker aims to simplify personal finance management. Users can easily add, edit, and delete expenses, categorize them for better analysis, and filter expenses by date range and category. The application provides a clear overview of spending through interactive charts and detailed expense lists.

## Development Environment Setup

This project utilizes Laravel Sail for a streamlined development environment using Docker. Follow these steps to set up the project:

1.  **Install PHP:**

    ```bash
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt update
    sudo apt install php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-tokenizer php8.2-mysql -y
    sudo update-alternatives --set php /usr/bin/php8.2
    sudo apt install php-intl
    ```

    Verify PHP installation: `php -v`

2.  **Create Laravel Project (using Docker):**

    ```bash
    docker run --rm -v $(pwd):/app -w /app laravelsail/php82-composer:latest composer create-project --prefer-dist laravel/laravel expense-tracker
    cd expense-tracker
    sudo chown -R $USER:$USER .  # Set correct permissions
    ```

3.  **Install Laravel Sail:**

    ```bash
    composer require laravel/sail --dev
    php artisan sail:install  # Select MySQL and Redis
    ```

4.  **Install PestPHP (for testing):**

    ```bash
    composer require --dev pestphp/pest:^3.5.2 pestphp/pest-plugin-laravel --with-all-dependencies
    php artisan pest:install
    ```

5.  **Start the Docker Environment:**

    ```bash
    ./vendor/bin/sail up -d
    ```

    -   Stop Sail: `./vendor/bin/sail down`
    -   View Logs: `./vendor/bin/sail logs`
    -   Enter Container: `./vendor/bin/sail shell`

6.  **Database Migrations and Models:**

    ```bash
    ./vendor/bin/sail artisan make:migration create_categories_table --create=categories
    ./vendor/bin/sail artisan make:migration create_expenses_table --create=expenses
    ./vendor/bin/sail artisan migrate

    ./vendor/bin/sail artisan make:model Category
    ./vendor/bin/sail artisan make:model Expense
    ```

7.  **Controllers:**

    ```bash
    ./vendor/bin/sail artisan make:controller CategoryController
    ./vendor/bin/sail artisan make:controller ExpenseController
    ./vendor/bin/sail artisan make:controller Api/CategoryController --api
    ./vendor/bin/sail artisan make:controller Api/ExpenseController --api
    ```

8.  **Policies:**

    ```bash
    artisan make:policy CategoryPolicy --model=Category
    artisan make:policy ExpensePolicy --model=Expense
    ```

9.  **Frontend Dependencies:**

    ```bash
    npm install axios
    ```

    ## Usage

10. Access the application in your browser at `http://localhost` (or the appropriate URL provided by Sail).
11. [Describe the basic workflow of the application, similar to the previous example].

## API Endpoints

(List all the API endpoints and their functionalities, as described in the previous example).

## Database Schema

(Provide a brief description of the main database tables and their columns, as described in the previous example).

## Testing

PestPHP is used for testing. You can run the tests using:

```bash
./vendor/bin/sail test

## Features

-   **Expense Management:** Add, edit, and delete expense records with descriptions, amounts, and categories.
-   **Categorization:** Assign expenses to predefined or custom categories for detailed spending analysis.
-   **Filtering:** Filter expenses by date range and category to view specific spending patterns.
-   **Data Visualization:** Interactive charts provide a visual representation of expense distribution across categories.
-   **Detailed Expense List:** A sortable and searchable table displays all expenses with relevant details.
-   **API:** A well-defined API allows for interaction with the expense data.
-   **Security:** Implemented policies for `Category` and `Expense` models to control access.

## Technologies Used

-   **Backend:** Laravel 11 (PHP Framework)
-   **Database:** MySQL
-   **Caching:** Redis
-   **Frontend:** jQuery, HTML, CSS
-   **Testing:** PestPHP
-   **DevOps:** Docker, Laravel Sail
-   **Packages:**
    -   `laravel/sail` (for Docker environment)
    -   `pestphp/pest` and `pestphp/pest-plugin-laravel` (for testing)
    -   `axios` (for making HTTP requests from the frontend)

## Development Environment Setup

(Same as previous example)

## Project Structure

This section outlines the key files and directories in the project:

-   **Models:**
    -   `app/Models/Category.php`
    -   `app/Models/Expense.php`
-   **Controllers:**
    -   `app/Http/Controllers/CategoryController.php`
    -   `app/Http/Controllers/ExpenseController.php`
    -   `app/Http/Controllers/Api/CategoryController.php`
    -   `app/Http/Controllers/Api/ExpenseController.php`
-   **Policies:**
    -   `app/Policies/CategoryPolicy.php`
    -   `app/Policies/ExpensePolicy.php`
-   **Routes:**
    -   `routes/api.php` (API routes)
    -   `routes/web.php` (Web routes)
-   **Frontend Views (Blade):**
    -   `resources/views/dashboard.blade.php` (Main dashboard view)
    -   `resources/views/expenses/add.blade.php` (View for adding expenses)
    -   `resources/views/layouts/app.blade.php` (Layout file)
    -   (Any other relevant blade files)
-   **Frontend JavaScript:**
    -   `resources/js/app.js` (Main JavaScript file)
    -   `resources/js/components/chart.js` (Chart rendering component)
    -   `resources/js/components/loading.js` (Loading indicator component)
-   **Tests:**
    -   `tests/Feature` (Feature tests)
    -   `tests/Unit` (Unit tests)
-   **Configuration:**
    -   `.env` (Environment variables)
    -   `config/database.php` (Database configuration)
    -   `config/cors.php` (CORS Configuration)
```

## API Endpoints

The following API endpoints are available (all routes are prefixed with `/api` and require Sanctum authentication unless otherwise noted):

**Category API:**

-   `GET /api/categories`: Retrieves all expense categories.
-   `POST /api/categories`: Creates a new expense category.
-   `GET /api/categories/{category}`: Retrieves a specific expense category.
-   `PUT /api/categories/{category}`: Updates an existing expense category.
-   `DELETE /api/categories/{category}`: Deletes an expense category.

**Expense API:**

-   `GET /api/expenses`: Retrieves all expenses (optionally filtered by date and category).
-   `POST /api/expenses`: Creates a new expense.
-   `GET /api/expenses/{expense}`: Retrieves a specific expense.
-   `PUT /api/expenses/{expense}`: Updates an existing expense.
-   `DELETE /api/expenses/{expense}`: Deletes an expense.
-   `GET /api/expense-summary`: Retrieves a summarized view of expenses, grouped by category.

## Database Schema

The application uses a MySQL database with the following tables:

-   **`users`:** (Standard Laravel users table)
    -   `id`
    -   `name`
    -   `email`
    -   `password`
    -   `...` (other default user fields)
-   **`categories`:**
    -   `id`
    -   `name`
    -   `user_id` (Foreign key referencing the `users` table)
    -   `created_at`
    -   `updated_at`
-   **`expenses`:**
    -   `id`
    -   `amount`
    -   `description`
    -   `category_id` (Foreign key referencing the `categories` table)
    -   `user_id` (Foreign key referencing the `users` table)
    -   `created_at`
    -   `updated_at`

## Testing

PestPHP is used for testing. You can run the tests using:

```bash
./vendor/bin/sail test
```

## Future Enhancements

-   Frontend Improvements: Enhance the user interface and user experience with more interactive components, better form validation, and improved data visualization.
-   Consider using a more modern JavaScript framework like Vue.js or React for a richer UI.
-   Advanced Reporting: Implement more sophisticated reporting features, such as generating reports in different formats (PDF, CSV), and providing more detailed analysis of spending habits over time.
-   Budgeting: Add functionality to set budgets for different categories and track progress against those budgets.
-   User Roles: Implement user roles (e.g., admin, regular user) with different permissions.
-   Social Login: Integrate social login providers (e.g., Google, Facebook) for easier user registration.
-   API Documentation: Create comprehensive API documentation using a tool like Swagger or Postman.
-   Improved Security: Implement additional security measures, such as input validation and protection against common web vulnerabilities.
-   Automated Deployment: Set up automated deployment pipelines to streamline the deployment process.
