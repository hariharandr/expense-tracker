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
                    <div class="mb-4 flex flex-wrap">
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
                                <option value="">All Categories</option>
                            </select>
                        </div>
                        <div class="mt-6 ml-4">
                            <x-primary-button id="apply-filter">Apply Filter</x-primary-button>
                        </div>
                    </div>

                    <div id="expense-summary-chart" class="mt-8">
                        <canvas id="expenseChart"></canvas>
                    </div>

                    <div class="mt-8 w-full overflow-x-auto"> {{-- Table container --}}
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 table-auto">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                </tr>
                            </thead>
                            <tbody id="expense-list-container"> </tbody> {{-- Correct <tbody> --}}
                        </table>
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

    <x-slot name="scripts">
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            $(document).ready(function() {
                window.dashboard = new window.Dashboard();
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot>
</x-app-layout>