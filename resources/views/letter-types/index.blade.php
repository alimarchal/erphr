<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Letter Types" :createRoute="route('letter-types.create')"
            createLabel="Add Letter Type" backRoute="settings.index" />
    </x-slot>

    <x-filter-section :action="route('letter-types.index')">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <x-label for="filter_name" value="Letter Type Name" />
                <x-input id="filter_name" name="filter[name]" type="text" class="mt-1 block w-full"
                    :value="request('filter.name')" placeholder="Search by name..." />
            </div>

            <div>
                <x-label for="filter_code" value="Code" />
                <x-input id="filter_code" name="filter[code]" type="text" class="mt-1 block w-full"
                    :value="request('filter.code')" placeholder="Search by code..." />
            </div>

            <div>
                <x-label for="filter_requires_reply" value="Requires Reply" />
                <select id="filter_requires_reply" name="filter[requires_reply]"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">-- All --</option>
                    <option value="1" {{ request('filter.requires_reply') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('filter.requires_reply') == '0' ? 'selected' : '' }}>No</option>
                </select>
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

    <x-data-table :items="$letterTypes" :headers="[
        ['label' => '#', 'align' => 'text-center'],
        ['label' => 'Name'],
        ['label' => 'Code', 'align' => 'text-center'],
        ['label' => 'Requires Reply', 'align' => 'text-center'],
        ['label' => 'Reply Days'],
        ['label' => 'Status', 'align' => 'text-center'],
        ['label' => 'Created Date', 'align' => 'text-center'],
        ['label' => 'Actions', 'align' => 'text-center'],
    ]" emptyMessage="No letter types found."
        :emptyRoute="route('letter-types.create')" emptyLinkText="Add a letter type">
        @foreach ($letterTypes as $index => $letterType)
            <tr class="border-b border-gray-200 text-sm">
                <td class="py-1 px-2 text-center">
                    {{ $letterTypes->firstItem() + $index }}
                </td>
                <td class="py-1 px-2 font-semibold">
                    <a href="{{ route('letter-types.show', $letterType) }}" class="text-indigo-600 hover:text-indigo-900">
                        {{ $letterType->name }}
                    </a>
                </td>
                <td class="py-1 px-2 text-center">
                    @if($letterType->code)
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                            {{ $letterType->code }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="py-1 px-2 text-center">
                    @if($letterType->requires_reply)
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            Yes
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                            No
                        </span>
                    @endif
                </td>
                <td class="py-1 px-2">
                    @if($letterType->default_days_to_reply)
                        <span class="text-xs text-gray-600">{{ $letterType->default_days_to_reply }} days</span>
                    @else
                        <span class="text-gray-400 text-xs">-</span>
                    @endif
                </td>
                <td class="py-1 px-2 text-center">
                    @if($letterType->is_active)
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                            Inactive
                        </span>
                    @endif
                </td>
                <td class="py-1 px-2 text-center text-gray-600">
                    {{ $letterType->created_at->format('M d, Y') }}
                </td>
                <td class="py-1 px-2 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('letter-types.edit', $letterType) }}"
                            class="text-indigo-600 hover:text-indigo-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('letter-types.toggle', $letterType) }}"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('letter-types.destroy', $letterType) }}"
                            onsubmit="return confirm('Are you sure?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>
</x-app-layout>
