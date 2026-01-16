<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Category" backRoute="correspondence-categories.index" />
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-status-message class="mb-4 mt-4 shadow-md" />
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <x-validation-errors class="mb-4 mt-4" />
                    <form method="POST" action="{{ route('correspondence-categories.update', $category) }}">
                        @csrf
                        @method('PUT')

                        @include('correspondence.categories.partials.form-fields', [
                            'category' => $category,
                        ])

                        <div class="flex items-center justify-end mt-6">
                            <x-button class="ml-4">
                                Update Category
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
