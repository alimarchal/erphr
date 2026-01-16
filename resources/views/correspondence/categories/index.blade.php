<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Correspondence Categories" :createRoute="route('correspondence-categories.create')"
            createLabel="Add Category" backRoute="settings.index" />
    </x-slot>

    <x-filter-section :action="route('correspondence-categories.index')">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <x-label for="filter_name" value="Category Name" />
                <x-input id="filter_name" name="filter[name]" type="text" class="mt-1 block w-full"
                    :value="request('filter.name')" placeholder="Search by name..." />
            </div>

            <div>
                <x-label for="filter_code" value="Code" />
                <x-input id="filter_code" name="filter[code]" type="text" class="mt-1 block w-full"
                    :value="request('filter.code')" placeholder="Search by code..." />
            </div>

            <div>
                <x-label for="filter_is_active" value="Status" />
                <select id="filter_is_active" name="filter[is_active]"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">-- All --</option>
                    <option value="1" {{ request('filter.is_active') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('filter.is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div>
                <x-label for="filter_created_from" value="Created From" />
                <x-input id="filter_created_from" name="filter[created_from]" type="date" class="mt-1 block w-full"
                    :value="request('filter.created_from')" />
            </div>

            <div>
                <x-label for="filter_created_to" value="Created To" />
                <x-input id="filter_created_to" name="filter[created_to]" type="date" class="mt-1 block w-full"
                    :value="request('filter.created_to')" />
            </div>
        </div>
    </x-filter-section>

    <x-data-table :items="$categories" :headers="[
        ['label' => '#', 'align' => 'text-center'],
        ['label' => 'Name'],
        ['label' => 'Code', 'align' => 'text-center'],
        ['label' => 'Parent'],
        ['label' => 'Status', 'align' => 'text-center'],
        ['label' => 'Created By'],
        ['label' => 'Created Date', 'align' => 'text-center'],
        ['label' => 'Actions', 'align' => 'text-center'],
    ]" emptyMessage="No categories found."
        :emptyRoute="route('correspondence-categories.create')" emptyLinkText="Add a category">
        @foreach ($categories as $index => $category)
            <tr class="border-b border-gray-200 text-sm">
                <td class="py-1 px-2 text-center">
                    {{ $categories->firstItem() + $index }}
                </td>
                <td class="py-1 px-2 font-semibold">
                    {{ $category->name }}
                </td>
                <td class="py-1 px-2 text-center">
                    @if($category->code)
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">
                            {{ $category->code }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="py-1 px-2">
                    @if($category->parent)
                        <span class="text-xs text-gray-600">{{ $category->parent->name }}</span>
                    @else
                        <span class="text-gray-400 text-xs">Root</span>
                    @endif
                </td>
                <td class="py-1 px-2 text-center">
                    <form method="POST" action="{{ route('correspondence-categories.toggle', $category) }}"
                        style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full transition-colors
                            {{ $category->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                </td>
                <td class="py-1 px-2">
                    {{ $category->creator?->name ?? '-' }}
                </td>
                <td class="py-1 px-2 text-center">
                    {{ $category->created_at?->format('d-m-Y H:i') ?? '-' }}
                </td>
                <td class="py-1 px-2 text-center">
                    <div class="flex justify-center space-x-2">
                        <a href="{{ route('correspondence-categories.show', $category) }}"
                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-md transition-colors duration-150"
                            title="View">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <a href="{{ route('correspondence-categories.edit', $category) }}"
                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-800 hover:bg-green-100 rounded-md transition-colors duration-150"
                            title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>
</x-app-layout>