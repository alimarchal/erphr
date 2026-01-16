<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Divisions" :createRoute="route('divisions.create')" createLabel="Add Division"
            backRoute="dashboard" />
    </x-slot>

    <x-filter-section :action="route('divisions.index')">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <x-label for="filter_name" value="Division Name" />
                <x-input id="filter_name" name="filter[name]" type="text" class="mt-1 block w-full"
                    :value="request('filter.name')" placeholder="Search by name..." />
            </div>

            <div>
                <x-label for="filter_short_name" value="Short Name" />
                <x-input id="filter_short_name" name="filter[short_name]" type="text" class="mt-1 block w-full"
                    :value="request('filter.short_name')" placeholder="Search by short name..." />
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

    <x-data-table :items="$divisions" :headers="[
        ['label' => '#', 'align' => 'text-center'],
        ['label' => 'Name'],
        ['label' => 'Short Name', 'align' => 'text-center'],
        ['label' => 'Created By'],
        ['label' => 'Created At', 'align' => 'text-center'],
        ['label' => 'Actions', 'align' => 'text-center'],
    ]" emptyMessage="No divisions found."
        :emptyRoute="route('divisions.create')" emptyLinkText="Add a division">
        @foreach ($divisions as $index => $division)
            <tr class="border-b border-gray-200 text-sm">
                <td class="py-1 px-2 text-center">
                    {{ $divisions->firstItem() + $index }}
                </td>
                <td class="py-1 px-2 font-semibold">
                    {{ $division->name }}
                </td>
                <td class="py-1 px-2 text-center">
                    <span
                        class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                        {{ $division->short_name }}
                    </span>
                </td>
                <td class="py-1 px-2">
                    {{ $division->creator?->name ?? '-' }}
                </td>
                <td class="py-1 px-2 text-center">
                    {{ $division->created_at?->format('d-m-Y H:i') ?? '-' }}
                </td>
                <td class="py-1 px-2 text-center">
                    <div class="flex justify-center space-x-2">
                        <a href="{{ route('divisions.show', $division) }}"
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
                        <a href="{{ route('divisions.edit', $division) }}"
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