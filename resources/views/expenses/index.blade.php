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

                    <div id="expense-list-container">
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

                    <div id="expense-summary-chart" class="mt-8">
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="loading-indicator" class="fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-200 dark:border-gray-600"></div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('js/app.js') }}"></script>
    </x-slot>
</x-app-layout>