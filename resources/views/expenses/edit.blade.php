<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Expense') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="add-expense-form">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" name="amount" type="number" class="mt-1 w-full" required autofocus />
                            <div id="amount-error" class="text-red-600"></div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 w-full" required />
                            <div id="description-error" class="text-red-600"></div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 w-full" required>
                                {{-- Options will be populated by JavaScript --}}
                            </select>
                            <div id="category_id-error" class="text-red-600"></div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="expense_date" :value="__('Expense Date')" />
                            <x-text-input id="expense_date" name="expense_date" type="date" class="mt-1 w-full" required />
                            <div id="expense_date-error" class="text-red-600"></div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Add Expense') }}
                            </x-primary-button>
                        </div>
                    </form>
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
    </x-slot>
</x-app-layout>