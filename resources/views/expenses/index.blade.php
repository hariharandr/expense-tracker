<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Expenses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4 flex flex-wrap"> {{-- Filter Section --}}
                        <div class="mr-4">
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 w-full" />
                        </div>
                        <div class="mr-4">
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 w-full" />
                        </div>
                        <div>
                            <x-input-label for="category_filter" :value="__('Category')" />
                            <select id="category_filter" name="category_filter" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 w-full">
                                <option value="">All Categories</option> {{-- Default option --}}
                            </select>
                        </div>
                        <div class="mt-6 ml-4"> {{-- Apply Filter Button --}}
                            <x-primary-button id="apply-filter">Apply Filter</x-primary-button>
                        </div>
                    </div>

                    <div id="expense-list-container">
                        {{-- Expenses will be listed here --}}
                    </div>

                    <div id="no-expenses" class="hidden mt-4">
                        <p class="text-gray-600 dark:text-gray-400">No expenses found.</p>
                    </div>

                    <div class="mt-4">
                        <x-primary-button id="add-expense-button">
                            <a href="{{ route('expenses.add') }}">
                                {{ __('Add Expense') }}
                            </a>
                        </x-primary-button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- <div id="loading-indicator" class="fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-200 dark:border-gray-600"></div>
    </div> -->

    <div id="error-message" class="fixed top-0 left-0 w-full h-full bg-red-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <p class="text-white">An error occurred. Please try again.</p>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            $(document).ready(function() {
                window.expensesList = new window.ExpensesList();
            });
        </script>
    </x-slot>
</x-app-layout>