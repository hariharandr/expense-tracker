<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div id="category-list-container">
                        {{-- Categories will be listed here --}}
                    </div>

                    <div id="no-categories" class="hidden mt-4">
                        <p class="text-gray-600 dark:text-gray-400">No categories found. Add a new category below.</p>
                    </div>

                    <div id="category-form-container" class="mt-8"> {{-- Added margin top --}}
                        <div id="category-form-title" class="text-lg font-medium mb-2">Add Category</div>
                        <form id="add-category-form" action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 w-full" required autofocus />
                                <div id="name-error" class="text-red-600"></div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Save') }}
                                </x-primary-button>
                            </div>
                        </form>
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
        <script src="{{ asset('js/components/category-form.js') }}"></script> {{-- Include the new JS file --}}
        <script>
            $(document).ready(function() {
                const categoryForm = new window.CategoryForm('#add-category-form');
            });
        </script>
    </x-slot>
</x-app-layout>