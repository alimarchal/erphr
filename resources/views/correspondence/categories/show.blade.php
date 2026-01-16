<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ $category->name }}" backRoute="correspondence-categories.index" />
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Details Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Category Details</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <div class="px-6 py-4 flex justify-between items-center">
                                <span class="text-gray-600">Name</span>
                                <span class="font-medium text-gray-900">{{ $category->name }}</span>
                            </div>
                            <div class="px-6 py-4 flex justify-between items-center">
                                <span class="text-gray-600">Code</span>
                                <span class="font-medium text-gray-900">{{ $category->code ?? '-' }}</span>
                            </div>
                            <div class="px-6 py-4 flex justify-between items-center">
                                <span class="text-gray-600">Status</span>
                                <span>
                                    @if ($category->is_active)
                                        <flux:badge color="lime">Active</flux:badge>
                                    @else
                                        <flux:badge>Inactive</flux:badge>
                                    @endif
                                </span>
                            </div>
                            <div class="px-6 py-4 flex justify-between items-center">
                                <span class="text-gray-600">Parent Category</span>
                                <span class="font-medium text-gray-900">
                                    @if ($category->parent)
                                        <a href="{{ route('correspondence-categories.show', $category->parent) }}"
                                            class="text-blue-600 hover:text-blue-800 underline">
                                            {{ $category->parent->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </span>
                            </div>
                            <div class="px-6 py-4 flex justify-between items-center">
                                <span class="text-gray-600">Created By</span>
                                <span class="font-medium text-gray-900">{{ $category->creator?->name ?? '-' }}</span>
                            </div>
                            <div class="px-6 py-4 flex justify-between items-center">
                                <span class="text-gray-600">Created At</span>
                                <span
                                    class="font-medium text-gray-900">{{ $category->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="px-6 py-4 flex justify-between items-center">
                                <span class="text-gray-600">Last Updated By</span>
                                <span class="font-medium text-gray-900">{{ $category->updater?->name ?? '-' }}</span>
                            </div>
                            <div class="px-6 py-4 flex justify-between items-center">
                                <span class="text-gray-600">Last Updated At</span>
                                <span
                                    class="font-medium text-gray-900">{{ $category->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('correspondence-categories.edit', $category) }}">
                            <x-button>
                                Edit Category
                            </x-button>
                        </a>
                        <form method="POST" action="{{ route('correspondence-categories.toggle', $category) }}"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <x-button type="submit" variant="secondary">
                                @if ($category->is_active)
                                    Deactivate Category
                                @else
                                    Activate Category
                                @endif
                            </x-button>
                        </form>
                    </div>
                </div>

                <!-- Related Data Card -->
                <div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Related Data</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <div class="px-6 py-4">
                                <div class="text-sm text-gray-600 mb-1">Subcategories</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $category->children->count() }}</div>
                            </div>
                            <div class="px-6 py-4">
                                <div class="text-sm text-gray-600 mb-1">Correspondences</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $category->correspondences->count() }}
                                </div>
                            </div>
                        </div>

                        @if ($category->children->count() > 0)
                            <div class="p-6 border-t border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">Subcategories</h3>
                                <div class="space-y-2">
                                    @foreach ($category->children as $child)
                                        <a href="{{ route('correspondence-categories.show', $child) }}"
                                            class="block text-sm text-blue-600 hover:text-blue-800 underline">
                                            {{ $child->name }}
                                            @if (!$child->is_active)
                                                <span class="ml-2 text-xs text-gray-500">(Inactive)</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>